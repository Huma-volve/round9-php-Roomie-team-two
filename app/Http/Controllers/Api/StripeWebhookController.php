<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\ConfirmationEmailService;
use App\Services\PaymentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    use ApiResponseTrait;

    protected $paymentRepo;
    protected $paymentService;


    public function __construct(PaymentRepository $paymentRepo, PaymentService $paymentService)
    {
        $this->paymentRepo = $paymentRepo;
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook signature: ' . $e->getMessage());
            return response()->json(['status' => 'invalid signature'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Signature Verification Failed: ' . $e->getMessage());
            return response()->json(['status' => 'verification failed'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            case 'charge.refunded':
                $this->handleChargeRefunded($event->data->object);
                break;
            default:
                Log::info('Received unknown event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        try {
            $payment = $this->paymentRepo->findByStripePaymentIntentId($paymentIntent->id);
            if (!$payment) {
                Log::warning('Payment not found for Stripe intent: ' . $paymentIntent->id);
                return;
            }

            $payment = $this->paymentService->confirmPayment($paymentIntent->id, 'card');

            // Send Confirmation Email
            ConfirmationEmailService::send($payment);

            Log::info("Payment succeeded for reservation: {$payment->reservation_code}");
        } catch (\Exception $e) {
            Log::error("Error handling payment success: {$e->getMessage()}");
        }
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentIntentFailed($paymentIntent)
    {
        try {
            $payment = $this->paymentRepo->findByStripePaymentIntentId($paymentIntent->id);
            if (!$payment) {
                Log::warning('Payment not found for Stripe intent: ' . $paymentIntent->id);
                return;
            }

            $payment->update([
                'payment_status' => 'failed'
            ]);
            Log::warning("Payment failed for reservation: {$payment->reservation_code}");
        } catch (\Exception $e) {
            Log::error("Error handling payment failure: {$e->getMessage()}");
        }
    }
    /**
     * Handle refund
     */
    private function handleChargeRefunded($charge)
    {
        try {
            $payment = $this->paymentRepo->findByStripePaymentIntentId($charge->payment_intent);
            if (!$payment) {
                Log::warning('Payment not found for Stripe intent: ' . $charge->payment_intent);
                return;
            }
            $refundedAmount = $charge->amount_refunded / 100;
            $payment->update([
                'amount_paid' => $payment->amount_paid - $refundedAmount,
                'payment_status' => $payment->amount_paid <= 0 ? 'refunded' : 'partially_refunded'
            ]);

            Log::info("Payment refunded for reservation: {$payment->reservation_code}");
        } catch (\Exception $e) {
            Log::error("Error handling refund: {$e->getMessage()}");
        }
    }
}
