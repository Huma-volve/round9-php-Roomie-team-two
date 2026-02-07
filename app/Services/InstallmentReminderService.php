<?php

namespace App\Services;

use App\Mail\InstallmentOverdueMail;
use App\Mail\InstallmentReminderMail;
use App\Models\InstallmentSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InstallmentReminderService
{
    /**
     * Send email reminders for upcoming installments
     */
    public function sendUpcomingReminders(): void
    {
        try {
            // Get Installments that are due in the next 3 days
            $upcomingInstallments = InstallmentSchedule::where('status', 'pending')
                ->whereBetween('due_date', [now(), now()->addDays(3)])
                ->where('reminder_sent', false)
                ->get();

            foreach ($upcomingInstallments as $installment) {
                $user = $installment->payment->booking->user;

                Mail::to($user->email)
                    ->send(new InstallmentReminderMail($installment));

                $installment->update([
                    'reminder_sent' => true,
                    'reminder_sent_at' => now()
                ]);

                Log::info("Installment Reminder Email sent for installment: {$installment->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send installment reminders: {$e->getMessage()}");
        }
    }

    public function handleOverdueInstallments()
    {
        try {
            $overdueInstallments = InstallmentSchedule::where('status', 'pending')
                ->where('due_date', '<', now())
                ->get();

            foreach ($overdueInstallments as $installment) {
                $installment->update(['status' => 'overdue']);

                $user = $installment->payment->booking->user;

                Mail::to($user->email)
                    ->send(new InstallmentOverdueMail($installment));

                Log::warning("Installment Overdue Email sent for installment: {$installment->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to handle overdue installments: {$e->getMessage()}");
        }
    }
}
