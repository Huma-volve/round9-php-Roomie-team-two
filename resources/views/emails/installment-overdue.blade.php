<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }

            .alert {
                background-color: #fff3cd;
                border: 1px solid #ffc107;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
            }

            .button {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                margin: 20px 0;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Payment Reminder</h2>
            <h3>This installment is overdue</h3>

            <div class="alert">
                <p><strong>Installment #{{ $installment->installment_number }} is overdue</strong></p>
                <p>Amount: <strong>${{ number_format($installment->amount, 2) }}</strong></p>
                <p>Due Date: <strong>{{ $installment->due_date->format('M d, Y') }}</strong></p>
            </div>

            <p>Reservation Code: <strong>{{ $payment->reservation_code }}</strong></p>
            <p>Property: <strong>{{ $booking->property->title }}</strong></p>

            <p style="margin-top: 30px; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px;">
                If you have any questions, please contact support@roomie.com
            </p>
        </div>
    </body>

</html>
