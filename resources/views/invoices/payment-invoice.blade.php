<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
            }

            .invoice {
                max-width: 900px;
                margin: 0 auto;
                padding: 40px;
            }

            .header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 40px;
                border-bottom: 2px solid #4CAF50;
                padding-bottom: 20px;
            }

            .company {
                font-size: 24px;
                font-weight: bold;
                color: #4CAF50;
            }

            .invoice-info {
                text-align: right;
            }

            .section {
                margin: 30px 0;
            }

            .section-title {
                font-weight: bold;
                font-size: 14px;
                text-transform: uppercase;
                margin-bottom: 15px;
                border-bottom: 1px solid #ddd;
                padding-bottom: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                text-align: left;
                padding: 10px;
                background-color: #f5f5f5;
                border-bottom: 1px solid #ddd;
            }

            td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
            }

            .total-row {
                font-weight: bold;
                background-color: #f5f5f5;
            }

            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                font-size: 12px;
            }
        </style>
    </head>

    <body>
        <div class="invoice">
            <div class="header">
                <div class="company">ROOMIE</div>
                <div class="invoice-info">
                    <p><strong>INVOICE</strong></p>
                    <p>Reservation: {{ $payment->reservation_code }}</p>
                    <p>Date: {{ $invoice_date->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Bill To</div>
                <p>
                    <strong>{{ $billing_address->first_name }} {{ $billing_address->last_name }}</strong><br>
                    {{ $billing_address->street_address }}<br>
                    {{ $billing_address->city }}, {{ $billing_address->state }} {{ $billing_address->postal_code }}<br>
                    {{ $billing_address->country }}<br>
                    {{ $billing_address->email }}<br>
                    {{ $billing_address->phone }}
                </p>
            </div>

            <div class="section">
                <div class="section-title">Booking Details</div>
                <table>
                    <tr>
                        <td><strong>Property:</strong></td>
                        <td>{{ $booking->property->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>Room:</strong></td>
                        <td>{{ $booking->room ? $booking->room->room_number : 'Entire Unit' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Check-in:</strong></td>
                        <td>{{ $booking->check_in->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Check-out:</strong></td>
                        <td>{{ $booking->check_out->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nights:</strong></td>
                        <td>{{ $booking->getNightsCount() }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Payment Breakdown</div>
                <table>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                    <tr>
                        <td>Base Price ({{ $booking->getNightsCount() }} nights ×
                            ${{ number_format($booking->room ? $booking->room->price_per_month : $booking->property->price_per_month, 2) }})
                        </td>
                        <td style="text-align: right;">${{ number_format($payment->amount * 0.65, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Service Fee (5%)</td>
                        <td style="text-align: right;">${{ number_format($payment->service_fee, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Tax (15%)</td>
                        <td style="text-align: right;">${{ number_format($payment->tax, 2) }}</td>
                    </tr>
                    @if ($payment->move_in_protection)
                        <tr>
                            <td>Move-in Protection</td>
                            <td style="text-align: right;">${{ number_format($payment->move_in_protection_price, 2) }}
                            </td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td style="text-align: right;">${{ number_format($payment->amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            @if ($installments->count() > 0)
                <div class="section">
                    <div class="section-title">Payment Schedule</div>
                    <table>
                        <tr>
                            <th>Installment</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                        @foreach ($installments as $inst)
                            <tr>
                                <td>#{{ $inst->installment_number }}</td>
                                <td>${{ number_format($inst->amount, 2) }}</td>
                                <td>{{ $inst->due_date->format('M d, Y') }}</td>
                                <td>{{ ucfirst($inst->status) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif

            <div class="footer">
                <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
                <p><strong>Support:</strong> support@roomie.com | +1-800-ROOMIE-1</p>
                <p>Thank you for your business!</p>
            </div>
        </div>
    </body>

</html>
