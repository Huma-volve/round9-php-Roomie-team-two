<?php

namespace App\Mail;

use App\Models\InstallmentSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentOverdueMail extends Mailable
{
    use Queueable, SerializesModels;

    public InstallmentSchedule $installment;

    /**
     * Create a new message instance.
     */
    public function __construct(InstallmentSchedule $installment)
    {
        $this->installment = $installment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Payment Overdue - Installment #{$this->installment->installment_number}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.installment-overdue',
            with: [
                'installment' => $this->installment,
                'payment' => $this->installment->payment,
                'booking' => $this->installment->payment->booking,
            ]
        );
    }
}
