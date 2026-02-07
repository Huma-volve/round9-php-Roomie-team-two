<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\InstallmentSchedule;
use App\Models\Payment;
use App\Models\PersonalDetails;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class PaymentService
{
    protected $paymentRepo;
    protected $stripeService;

    public function __construct(PaymentRepository $paymentRepo, StripeService $stripeService)
    {
        $this->paymentRepo = $paymentRepo;
        $this->stripeService = $stripeService;
    }
    public function initiatePayment(Booking $booking, array $paymentData)
    {
        try {
            DB::beginTransaction();
            $totalAmount = $booking->total_price;
            $paymentType = $paymentData['payment_type'];
            $paymentMethod = $paymentData['payment_method'];

            if ($paymentType === 'full' || $paymentType === 'installment') {
                $amountToChargeNow  = $totalAmount;
            } else if ($paymentType === 'partial') {
                $amountToChargeNow  = $totalAmount * 0.33;
            } else {
                throw new Exception('Invalid payment type');
            }

            // Create payment
            $payment = $this->paymentRepo->create([
                'booking_id' => $booking->id,
                'reservation_code' => $this->generateReservationCode(),
                'payment_method' => $paymentMethod,
                'payment_type' => $paymentType,
                'payment_status' => 'pending',
                'amount' => $totalAmount,
                'amount_paid' => 0,
            ]);

            // Create Personal details
            $PersonalDetails = PersonalDetails::create([
                'payment_id' => $payment->id,
                'user_id' => $booking->user_id,
                'address_line' => $paymentData['personal_details']['address_line'],
                'city' => $paymentData['personal_details']['city'],
                'state' => $paymentData['personal_details']['state'] ?? null,
                'postal_code' => $paymentData['personal_details']['postal_code'] ?? null,
            ]);

            // Create Payment Intent
            if ($paymentMethod === 'card') {
                $paymentIntentData = $this->stripeService->createPaymentIntent($payment, $amountToChargeNow, $paymentData['stripe_payment_method_id'] ?? null);
            } else if ($paymentMethod === 'klarna') {
                // Handle Klarna payment
            }

            // Create Installment Schedule if needed
            if ($paymentType === 'partial') {
                $this->createPartialPaymentSchedule($payment, $totalAmount);
            }

            DB::commit();

            return [
                'payment_id' => $payment->id,
                'reservation_code' => $payment->reservation_code,
                'payment_intent' => $paymentIntentData,
                'payment_type' => $paymentType,
                'payment_method' => $paymentMethod,
                'amount_to_charge_now' => round($amountToChargeNow, 2),
                'total_amount' => $totalAmount,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment Initialization Error: ' . $e->getMessage());
            throw new Exception('Payment initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Confirm payment after successful stripe/klarna processing
     */
    public function confirmPayment($paymentIntentId, $paymentMethod)
    {
        try {
            DB::beginTransaction();

            $payment = $this->paymentRepo->findByStripePaymentIntentId($paymentIntentId);
            if (!$payment) {
                throw new Exception('Payment not found', 404);
            }

            // Verify Payment with the provider
            if ($paymentMethod === 'card') {
                $stripeData = $this->stripeService->confirmPayment($paymentIntentId);
                $amountPaid = $stripeData['amount_received'];
            }

            // Update Payment status

            $newAmountPaid = $payment->amount_paid + $amountPaid;


            if ($newAmountPaid >= $payment->amount) {
                $status = 'paid';
            } else if ($newAmountPaid > 0) {
                $status = 'partially_paid';
            } else {
                $status = 'pending';
            }
            // Update Payment status and amount
            $payment = $this->paymentRepo->update($payment, [
                'payment_status' => $status,
                'amount_paid' => $newAmountPaid,
                'paid_at' => $status === 'paid' ? now() : null,
            ]);

            // Update Booking status
            if ($status === 'paid' || $status === 'partially_paid') {
                $payment->booking->update(['status' => 'confirmed']);
            }

            DB::commit();
            return $payment->load('installmentSchedules');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment Confirmation Error: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Create partial payment schedule (33% now, 67% later)
     */
    private function createPartialPaymentSchedule(Payment $payment, float $totalAmount)
    {
        $secondPaymentDue = Carbon::now()->addDays(30); // 30 days later
        DB::transaction(function () use ($payment, $totalAmount, $secondPaymentDue) {
            InstallmentSchedule::create([
                'payment_id' => $payment->id,
                'installment_number' => 1,
                'amount' => $totalAmount * 0.33,
                'due_date' => now(),
                'status' => 'pending'
            ]);
            InstallmentSchedule::create([
                'payment_id' => $payment->id,
                'installment_number' => 2,
                'amount' => $totalAmount * 0.67,
                'due_date' => $secondPaymentDue,
                'status' => 'pending',
            ]);
        });
    }
    private function generateReservationCode()
    {

        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));

        return "ROM-{$date}-{$random}";
    }

    /**
     * Validate if a payment has been initiated for a given booking.
     *
     * @param Booking $booking
     * @return Payment|false
     */
    public function validatePaymentHasBeenInitiated(Booking $booking)
    {
        $payment = Payment::where('booking_id', $booking->id)
            ->whereIn('payment_status', ['pending', 'partially_paid', 'paid'])
            ->first();
        if ($payment) {
            return $payment;
        }
        return false;
    }
}
