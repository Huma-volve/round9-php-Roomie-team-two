<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Property;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    private const TAX_RATE = 0.15;
    private const SERVICE_FEE_RATE = 0.05;
    private const MOVE_IN_PROTECTION_PRICE = 100;
    public function calculateBookingPrice(
        int $property_id,
        ?int $room_id,
        string $checkIn,
        string $checkOut,
        bool $move_in_protection = false
    ): array {
        try {
            $property = Property::findOrFail($property_id);

            // Validate property and room relationship
            $this->validatePropertyAndRoom($property, $room_id);

            // Get price per night based on room or property
            if ($room_id !== null) {
                $room = $property->rooms()->findOrFail($room_id);
                $price_per_night = $room->price_per_night;
            } else {
                $price_per_night = $property->price_per_night;
            }

            // Calculate total price
            $pricing_data = $this->calculateTotalPrice(
                $price_per_night,
                $checkIn,
                $checkOut,
                $move_in_protection
            );

            return $pricing_data;
        } catch (ModelNotFoundException $e) {
            Log::error('Property or Room not found: ' . $e->getMessage());
            throw new \Exception('Property or Room not found', 404);
        } catch (\Exception $e) {
            Log::error('Error calculating booking price: ' . $e->getMessage());
            throw $e;
        }
    }
    public function createBooking($request): Booking
    {
        try {
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            // Validate property and room
            $this->validatePropertyAndRoom(
                Property::findOrFail($request->property_id),
                $request->room_id ?? null
            );

            // Check availability
            $isAvailable = $this->checkAvailability(
                $request->property_id,
                $request->room_id ?? null,
                $checkIn,
                $checkOut
            );

            if (!$isAvailable) {
                throw new \Exception('This unit is not available for the selected dates', 409);
            }

            // Calculate pricing
            $pricing_data = $this->calculateBookingPrice(
                $request->property_id,
                $request->room_id ?? null,
                $request->check_in,
                $request->check_out,
                $request->move_in_protection ?? false
            );

            // Create booking with transaction
            $booking = DB::transaction(function () use ($request, $checkIn, $checkOut, $pricing_data) {
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'property_id' => $request->property_id,
                    'room_id' => $request->room_id ?? null,
                    'phone' => $request->phone,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'total_price' => $pricing_data['total_price'],
                    'move_in_protection' => $request->move_in_protection ?? false,
                    'move_in_protection_price' => $pricing_data['move_in_protection'] ?? 0,
                    'service_fee' => $pricing_data['service_fee'],
                    'tax' => $pricing_data['taxes'],
                    'special_requests' => $request->special_requests ?? null,
                    'cancellation_policy' => $request->cancellation_policy ?? 'moderate',
                    'status' => 'pending'
                ]);

                // Create guests
                foreach ($request->guests as $guest) {
                    Guest::create([
                        'booking_id' => $booking->id,
                        'user_id' => auth()->id(),
                        'first_name' => $guest['first_name'],
                        'last_name' => $guest['last_name'],
                        'email' => $guest['email'] ?? null,
                        'phone' => $guest['phone'] ?? null
                    ]);
                }
                return $booking;
            });

            return $booking;
        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * Retrieves all bookings for the given user id.
     *
     * @param int $user_id The user id to retrieve bookings for.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Booking[]
     *
     * @throws \Exception If there is an error retrieving the bookings.
     */
    public function getUserBookings(int $user_id)
    {
        try {
            $bookings = Booking::where('user_id', $user_id)
                ->with(['property', 'room', 'guests'])
                ->orderBy('created_at', 'desc')
                ->get();

            return $bookings;
        } catch (\Exception $e) {
            Log::error('Error retrieving user bookings: ' . $e->getMessage());
            throw new \Exception('Error retrieving bookings', 400);
        }
    }


    /**
     * Cancel a booking.
     *
     * @param \App\Models\Booking $booking The booking to cancel.
     *
     * @throws \Exception If the booking cannot be cancelled.
     */
    public function cancelBooking(Booking $booking)
    {
        try {
            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                throw new \Exception('Booking cannot be cancelled', 422);
            }
            $booking->update([
                'status' => 'cancelled'
            ]);
        } catch (\Exception $e) {
            Log::error('Error cancelling booking: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Validate a property and room selection
     *
     * @param Property $property
     * @param int|null $room_id
     *
     * @throws \Exception if room selection is required for room rentals and room_id is null
     * @throws \Exception if room_id is provided but room is not found
     * @throws \Exception if room_id is provided but room does not belong to the property
     */
    /*******  98470b50-685c-4fda-91cc-2cfb590e6347  *******/
    protected function validatePropertyAndRoom(Property $property, ?int $room_id): void
    {
        // If room rental, room_id is required
        if ($property->rent_type === 'room' && $room_id === null) {
            throw new \Exception('Room selection is required for room rentals', 422);
        }

        // If room_id provided, validate it belongs to property
        if ($room_id !== null) {
            $room = Room::find($room_id);

            if (!$room) {
                throw new \Exception('Room not found', 404);
            }

            if ($room->property_id !== $property->id) {
                throw new \Exception('Room does not belong to this property', 422);
            }
        }
    }
    /**
     * Check if a property is available for a given date range.
     *
     * @param int $property_id
     * @param int|null $room_id
     * @param Carbon $checkIn
     * @param Carbon $checkOut
     *
     * @throws \Exception if property or room configuration is invalid
     * @return bool true if property is available, false otherwise
     */
    protected function checkAvailability(
        int $property_id,
        ?int $room_id,
        Carbon $checkIn,
        Carbon $checkOut
    ): bool {
        try {
            $property = Property::findOrFail($property_id);

            if ($property->rent_type === 'room' && $room_id) {
                // Check room availability
                $isAvailable = !Booking::where('room_id', $room_id)
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($query) use ($checkIn, $checkOut) {
                        $query->whereBetween('check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('check_out', [$checkIn, $checkOut]);
                    })
                    ->exists();
            } else if ($property->rent_type === 'apartment' && !$room_id) {
                // Check apartment availability
                $isAvailable = !Booking::where('property_id', $property_id)
                    ->whereNull('room_id')
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($query) use ($checkIn, $checkOut) {
                        $query->whereBetween('check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('check_out', [$checkIn, $checkOut]);
                    })
                    ->exists();
            } else {
                throw new \Exception('Invalid property or room configuration', 422);
            }

            return $isAvailable;
        } catch (\Exception $e) {
            Log::error('Error checking availability: ' . $e->getMessage());
            throw $e;
        }
    }



    protected function calculateTotalPrice($price_per_night, $checkIn, $checkOut, $move_in_protection = false)
    {
        $checkInFormatted = Carbon::parse($checkIn);
        $checkOutFormatted = Carbon::parse($checkOut);
        $nights = $checkInFormatted->diffInDays($checkOutFormatted);

        $basePrice = $price_per_night * $nights;
        $serviceFee = $basePrice * self::SERVICE_FEE_RATE;
        $priceWithServiceFee = $basePrice + $serviceFee;
        $taxes = $priceWithServiceFee * self::TAX_RATE;
        $totalPrice = $priceWithServiceFee + $taxes;

        if ($move_in_protection) {
            $totalPrice += self::MOVE_IN_PROTECTION_PRICE;
        }
        $data = [
            'total_price' => $totalPrice,
            'taxes' => $taxes,
            'service_fee' => $serviceFee
        ];
        if ($move_in_protection) {
            $data['move_in_protection'] = self::MOVE_IN_PROTECTION_PRICE;
        }
        return $data;
    }
}
