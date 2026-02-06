<?php

namespace App\Services;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\String\s;

class InvoiceService
{

    /**
     * Generate an invoice PDF for a given payment.
     *
     * @param Payment $payment
     * @return string The path to the generated PDF file.
     * @throws Exception If there is an error generating the PDF.
     */
    public function generatePdf($payment): string
    {

        try {
            $booking = $payment->booking->load('property', 'room', 'guests');
            $personalDetails = $payment->personalDetails;
            $data = [
                'payment' => $payment,
                'booking' => $booking,
                'billing_address' => $personalDetails,
                'installments' => $payment->installmentSchedules,
                'invoice_date' => $payment->created_at,
                'due_date' => $payment->payment_due_date,
            ];
            // Render the invoice view and convert it to PDF
            $pdf = Pdf::loadView('invoices.payment-invoice', $data);

            $filename = "invoice-{$payment->reservation_code}.pdf";
            $path = storage_path("app/invoices/{$filename}");

            if (!file_exists(storage_path("app/invoices"))) {
                mkdir(storage_path("app/invoices"), 0755, true);
            }

            // Save the PDF to the specified path
            $pdf->save($path);

            return $path;
        } catch (Exception $e) {
            Log::error('Invoice PDF Generation Error: ' . $e->getMessage());
            throw new Exception('Failed to generate invoice PDF');
        }
    }
}