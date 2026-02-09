<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessWebhookPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:process-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending payments from stripe webhooks - fallback in case of webhook failure';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $paymentService = app(PaymentService::class);
        $pendingPayments = Payment::where('payment_status', 'pending')->get();
        if ($pendingPayments->isEmpty()) {
            $this->info('No pending payments found');
            return;
        }
        foreach ($pendingPayments as $payment) {
            try {
                if ($payment->stripe_payment_intent_id) {
                    $paymentService->confirmPayment($payment->stripe_payment_intent_id, 'card');
                    $this->info("Confirmed Stripe payment: {$payment->reservation_code}");
                }
            } catch (\Exception $e) {
                Log::error("Payment processing error: {$e->getMessage()}");
                $this->error("Failed to process {$payment->reservation_code}: {$e->getMessage()}");
            }
        }
        $this->info('Webhook payments processed successfully!');
    }
}
