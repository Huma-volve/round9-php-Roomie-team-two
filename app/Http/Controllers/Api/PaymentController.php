<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmPaymentRequest;
use App\Http\Requests\InitiatePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    use ApiResponseTrait;
    protected $paymentService;
    protected $invoiceService;

    public function __construct(PaymentService $paymentService, InvoiceService $invoiceService)
    {
        $this->paymentService = $paymentService;
        $this->invoiceService = $invoiceService;
    }
    public function initiatePayment(InitiatePaymentRequest $request): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($request->booking_id);
            // Check authorization
            if ($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return $this->errorResponse('Unauthorized', 403);
            }
            // Check if booking has already been paid
            $bookingPayment = $this->paymentService->validatePaymentHasBeenInitiated($booking);
            if ($bookingPayment) {
                return $this->errorResponse('Payment has already been initialized', 400, $bookingPayment);
            }

            $paymentData = $this->paymentService->initiatePayment($booking, $request->validated());

            return $this->successResponse('Payment initiated successfully', 200, $paymentData);
        } catch (\Exception $e) {
            Log::error('Payment Initiation Error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function confirmPayment(ConfirmPaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->confirmPayment($request->payment_intent_id, $request->payment_method);

            return $this->successResponse('Payment confirmed successfully', 200, [
                'reservation_code' => $payment->reservation_code,
                'payment' => $payment,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Confirmation Error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Retrieves payment details
     *
     * @param Payment $payment
     * @return JsonResponse
     * @throws \Throwable
     */
    public function show(Payment $payment): JsonResponse
    {
        try {
            if ($payment->booking->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized', 403);
            }
            return $this->successResponse('Payment details retrieved successfully', 200, [
                'payment' => $payment->load('booking', 'personalDetails', 'installmentSchedules'),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Retrieves all payments for the current user.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception If there is an error retrieving the payments.
     */
    public function userPayments(): JsonResponse
    {
        try {
            $payments = Payment::whereHas('booking', function ($query) {
                $query->where('user_id', Auth::id());
            })
                ->with('booking', 'personalDetails', 'installmentSchedules')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->successResponse('User payments retrieved', 200, $payments);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Downloads an invoice PDF for a given payment.
     *
     * @param Payment $payment
     * @throws \Exception If there is an error generating the PDF.
     */
    public function downloadInvoice(Payment $payment)
    {
        try {
            if ($payment->booking->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized', 403);
            }
            $pdfPath = $this->invoiceService->generatePdf($payment);

            return response()->download($pdfPath, "invoice-{$payment->reservation_code}.pdf")->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function paymentSummary(Payment $payment)
    {
        try {
            if ($payment->booking->user_id !== Auth::id()) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $summary = [
                'reservation_code' => $payment->reservation_code,
                'status' => $payment->payment_status,
                'payment_type' => $payment->payment_type,
                'payment_method' => $payment->payment_method,
                'total_amount' => $payment->amount,
                'amount_paid' => $payment->amount_paid,
                'amount_remaining' => (string)$payment->amount_remaining,
                'paid_at' => $payment->paid_at,
                'booking' => $payment->booking->load('property', 'room', 'guests'),
                'personal_details' => $payment->personalDetails,
                'installment_schedules' => $payment->installmentSchedules,
            ];
            return $this->successResponse('Payment summary', 200, $summary);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
