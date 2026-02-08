<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePaymentStatusRequest;
use App\Models\Payment;
use App\Services\Admin\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function index(Request $request)
    {
        $filteredPayments = $this->paymentService->applyFilters($request);
        return view('admin.payments.index', [
            'payments' => $filteredPayments,
            'paymentStatusOptions' => [
                'pending' => 'Pending',
                'partially_paid' => 'Partially Paid',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
            ],
            'paymentTypeOptions' => [
                'full' => 'Full',
                'partial' => 'Partial',
                'installment' => 'Installment',
            ],
            'paymentMethodOptions' => [
                'credit_card' => 'Credit Card',
                'paypal' => 'PayPal',
                'bank_transfer' => 'Bank Transfer',
                'cash_on_arrival' => 'Cash on Arrival',
            ],
            'payment_status' => $request->payment_status ?? null,
            'payment_type' => $request->payment_type ?? null,
            'user_id' => $request->user_id ?? null,
            'paid_from' => $request->paid_from ?? null,
            'paid_to' => $request->paid_to ?? null,
            'search' => $request->search ?? null,
        ]);
    }

    public function show($id)
    {
        try {
            $payment = $this->paymentService->getPaymentDetails($id);
            $availableActions = $this->paymentService->getAvailableActions($payment);

            return view('admin.payments.show', compact('payment', 'availableActions'));
        } catch (\Exception $e) {
            Log::error('Error loading payment details: ' . $e->getMessage());
            return back()->with('error', 'Error loading payment details');
        }
    }

    public function markAsPaid(Payment $payment)
    {
        try {
            $this->paymentService->markAsPaid($payment);
            return back()->with('success', 'Payment marked as paid successfully');
        } catch (\Exception $e) {
            Log::error('Error marking payment as paid: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function markAsFailed(Payment $payment)
    {
        try {
            $this->paymentService->markAsFailed($payment);
            return back()->with('success', 'Payment marked as failed');
        } catch (\Exception $e) {
            Log::error('Error marking payment as failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function refundPayment(Payment $payment)
    {
        try {
            $this->paymentService->refundPayment($payment);
            return back()->with('success', 'Payment refunded successfully');
        } catch (\Exception $e) {
            Log::error('Error refunding payment: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function updateStatus(UpdatePaymentStatusRequest $request, Payment $payment)
    {
        try {
            $validated = $request->validated();

            $payment = $this->paymentService->updatePaymentStatus(
                $payment,
                $validated['payment_status'],
                $validated['notes'] ?? null
            );

            return back()->with('success', 'Payment status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating payment status: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
