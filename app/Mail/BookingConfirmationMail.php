<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Booking Confirmation - Reservation {$this->payment->reservation_code}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'payment' => $this->payment,
                'booking' => $this->payment->booking->load('property', 'room', 'guests'),
                'billingAddress' => $this->payment->personalDetails,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $invoiceService = app(\App\Services\InvoiceService::class);
        $pdfPath = $invoiceService->generatePdf($this->payment);
        return [
            Attachment::fromPath($pdfPath)
                ->as("invoice-{$this->payment->reservation_code}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
