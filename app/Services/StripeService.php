<?php

namespace App\Services;

use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeService
{
    private $stripeClient;
    public function __construct()
    {
        $this->stripeClient = new StripeClient(config('services.stripe.secret_key'));
    }
    public function createPaymentIntent(Payment $payment, $amountToCharge, $paymentMethodId = null): array
    {
        try {
            $intentData = [
                'amount' => (int)($amountToCharge * 100),
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never'
                ],
                'metadata' => [
                    'payment_id' => $payment->id,
                    'booking_id' => $payment->booking_id,
                    'reservation_code' => $payment->reservation_code,
                    'payment_type' => $payment->payment_type,
                    'amount_charged' => $amountToCharge,
                    'total_booking_amount' => $payment->amount,
                ],
            ];

            if ($paymentMethodId) {
                $intentData['payment_method'] = $paymentMethodId;
                $intentData['confirm'] = true;
                $intentData['off_session'] = false;
            }
            $intent = $this->stripeClient->paymentIntents->create($intentData);


            $payment->update([
                'stripe_payment_intent_id' => $intent->id,
                'stripe_response' => json_encode($intent),
            ]);

            return [
                'client_secret' => $intent->client_secret,
                'payment_intent_id' => $intent->id,
                'amount_charged' => $amountToCharge,
                'status' => $intent->status
            ];
        } catch (Exception $e) {
            Log::error('Error creating Stripe payment intent: ' . $e->getMessage());
            throw new Exception('Failed to create stripe payment intent', 500);
        }
    }


    /**
     * Confirms a Stripe payment intent.
     *
     * @param string $paymentIntentId The payment intent id to confirm.
     *
     * @return array An array containing the payment intent status, amount received, and charges.
     *
     * @throws \Exception If there is an error confirming the payment intent.
     */
    public function confirmPayment(string $paymentIntentId): array
    {
        try {
            $intent = $this->stripeClient->paymentIntents->retrieve($paymentIntentId);
            return [
                'status' => $intent->status,
                'amount_received' => $intent->amount_received / 100,
            ];
        } catch (Exception $e) {
            Log::error('Stripe Payment Confirmation Error: ' . $e->getMessage());
            throw new Exception('Failed to confirm payment: ' . $e->getMessage());
        }
    }
}
