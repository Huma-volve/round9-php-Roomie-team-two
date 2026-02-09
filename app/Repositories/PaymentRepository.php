<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $data)
    {
        return Payment::create($data);
    }
    public function update(Payment $payment, array $data)
    {
        $payment->update($data);
        return $payment->fresh();
    }

    public function findByStripePaymentIntentId(string $paymentIntentId): ?Payment
    {
        return Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();
    }

    public function findById($id): ?Payment
    {
        return Payment::findOrFail($id);
    }
}