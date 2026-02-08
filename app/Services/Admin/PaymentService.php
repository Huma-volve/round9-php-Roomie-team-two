<?php

namespace App\Services\Admin;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private const PAGINATE_COUNT = 10;

    public function __construct(protected PaymentRepository $paymentRepository) {}

    public function applyFilters($request)
    {
        try {

            $query = Payment::query()->with('booking.user', 'booking.property', 'booking.room', 'personalDetails', 'installmentSchedules')
                ->latest('created_at');

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('payment_type')) {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->filled('user_id')) {
                $query->whereHas('booking', function ($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                });
            }

            if ($request->filled('paid_from')) {
                $query->whereDate('paid_at', '>=', $request->paid_from);
            }

            if ($request->filled('paid_to')) {
                $query->whereDate('paid_at', '<=', $request->paid_to);
            }

            // Search by reservation code or user name/email
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('reservation_code', 'like', '%' . $request->search . '%')
                        ->orWhereHas('booking.user', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%")
                                ->orWhere('email', 'like', "%{$request->search}%");
                        });
                });
            }

            $payments = $query->paginate(self::PAGINATE_COUNT);

            return $payments;
        } catch (\Exception $e) {
            Log::error('Error applying payment filters: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPaymentDetails($id)
    {
        try {
            $payment = $this->paymentRepository->findById($id);
            return $payment->load('booking.user', 'booking.property', 'booking.room', 'booking.guests', 'personalDetails', 'installmentSchedules');
        } catch (\Exception $e) {
            Log::error('Error fetching payment details: ' . $e->getMessage());
            throw $e;
        }
    }

    public function markAsPaid(Payment $payment): Payment
    {
        try {
            if ($payment->payment_status === 'paid') {
                throw new \Exception('Payment is already marked as paid', 422);
            }

            DB::beginTransaction();
            $payment->update([
                'payment_status' => 'paid',
                'amount_paid' => $payment->amount,
                'paid_at' => now(),
            ]);


            // Update booking status to confirmed
            $payment->booking->update(['status' => 'confirmed']);

            DB::commit();

            Log::info("Payment marked as paid: {$payment->reservation_code}");

            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking payment as paid: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mark payment as failed
     *
     * @param Payment $payment
     * @return Payment
     */
    public function markAsFailed(Payment $payment): Payment
    {
        try {
            if ($payment->payment_status === 'failed') {
                throw new \Exception('Payment is already marked as failed', 422);
            }

            $payment->update([
                'payment_status' => 'failed',
                'amount_paid' => 0,
                'paid_at' => null,
            ]);

            Log::warning("Payment marked as failed: {$payment->reservation_code}");

            return $payment;
        } catch (\Exception $e) {
            Log::error('Error marking payment as failed: ' . $e->getMessage());
            throw $e;
        }
    }


    public function refundPayment(Payment $payment): Payment
    {
        try {
            if ($payment->payment_status === 'refunded') {
                throw new \Exception('Payment is already refunded', 422);
            }

            if (!in_array($payment->payment_status, ['paid', 'partially_paid'])) {
                throw new \Exception('Only paid or partially paid payments can be refunded', 422);
            }

            DB::beginTransaction();

            $payment->update([
                'payment_status' => 'refunded',
                'amount_paid' => 0,
            ]);

            // Update booking status back to pending
            $payment->booking->update(['status' => 'pending']);

            DB::commit();

            Log::info("Payment refunded: {$payment->reservation_code}");

            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error refunding payment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatePaymentStatus(Payment $payment, string $newStatus, ?string $notes = null): Payment
    {
        try {
            $validStatuses = ['pending', 'partially_paid', 'paid', 'failed', 'refunded'];

            if (!in_array($newStatus, $validStatuses)) {
                throw new \Exception('Invalid payment status', 422);
            }

            DB::beginTransaction();

            $updateData = ['payment_status' => $newStatus];

            // Set paid_at if marking as paid
            if ($newStatus === 'paid') {
                $updateData['paid_at'] = now();
            }

            $payment->update($updateData);

            // Update booking status accordingly
            if ($newStatus === 'paid') {
                $payment->booking->update(['status' => 'confirmed']);
            } elseif ($newStatus === 'refunded') {
                $payment->booking->update(['status' => 'pending']);
            }

            DB::commit();

            Log::info("Payment status updated to {$newStatus}: {$payment->reservation_code}. Notes: {$notes}");

            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment status: ' . $e->getMessage());
            throw $e;
        }
    }


    public function getAvailableActions(Payment $payment): array
    {
        $actions = [];

        // Based on payment status, determine available actions
        switch ($payment->payment_status) {
            case 'pending':
                $actions[] = [
                    'action' => 'mark-paid',
                    'label' => 'Mark as Paid',
                    'class' => 'bg-green-600 hover:bg-green-700',
                    'icon' => 'check'
                ];
                $actions[] = [
                    'action' => 'mark-failed',
                    'label' => 'Mark as Failed',
                    'class' => 'bg-red-600 hover:bg-red-700',
                    'icon' => 'x'
                ];
                break;

            case 'partially_paid':
                $actions[] = [
                    'action' => 'mark-paid',
                    'label' => 'Complete Payment',
                    'class' => 'bg-green-600 hover:bg-green-700',
                    'icon' => 'check'
                ];
                $actions[] = [
                    'action' => 'refund',
                    'label' => 'Refund Partial',
                    'class' => 'bg-orange-600 hover:bg-orange-700',
                    'icon' => 'undo'
                ];
                break;

            case 'paid':
                $actions[] = [
                    'action' => 'refund',
                    'label' => 'Refund Payment',
                    'class' => 'bg-orange-600 hover:bg-orange-700',
                    'icon' => 'undo',
                    'confirm' => true
                ];
                break;

            case 'failed':
                // No actions available for failed payments
                break;

            case 'refunded':
                // No actions available for refunded payments
                break;
        }
        return $actions;
    }
}