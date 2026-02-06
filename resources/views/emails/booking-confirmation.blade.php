<!DOCTYPE html>
<html>

    <head>
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

            .header {
                background-color: #4CAF50;
                color: white;
                padding: 20px;
            }

            .section {
                margin: 20px 0;
                padding: 15px;
                border: 1px solid #ddd;
            }

            .detail {
                margin: 10px 0;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>Booking Confirmed!</h1>
                <p>Reservation Code: <strong>{{ $payment->reservation_code }}</strong></p>
            </div>

            <div class="section">
                <h2>Booking Details</h2>
                <div class="detail">
                    <strong>Property:</strong> {{ $booking->property->title }}
                </div>
                <div class="detail">
                    <strong>Check-in:</strong> {{ $booking->check_in->format('M d, Y') }}
                </div>
                <div class="detail">
                    <strong>Check-out:</strong> {{ $booking->check_out->format('M d, Y') }}
                </div>
                <div class="detail">
                    <strong>Total Price:</strong> {{ $payment->amount }}
                </div>
            </div>

            <div class="section">
                <h2>Guests</h2>
                @foreach ($booking->guests as $guest)
                    <div class="detail">
                        {{ $guest->first_name }} {{ $guest->last_name }}
                        ({{ $guest->phone }})
                    </div>
                @endforeach
            </div>

            <div class="section">
                <p>Invoice is attached to this email.</p>
                <p>Thank you for booking with Roomie!</p>
            </div>
        </div>
    </body>

</html>
