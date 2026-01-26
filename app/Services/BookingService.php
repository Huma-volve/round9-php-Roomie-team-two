<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingService
{
    private const TAX_RATE = 0.15;
    private const SERVICE_FEE_RATE = 0.05;
    private const MOVE_IN_PROTECTION_PRICE = 100;
    public function calculateBookingPrice($property_id, $room_id = null, $checkIn, $checkOut)
    {
        try {
            $property = Property::findOrFail($property_id);
            if ($room_id !== null) {
                if ($property->rent_type == 'room') {
                    $room = $property->rooms()->findOrFail($room_id);
                    $price_per_night = $room->price_per_night;
                    $pricing_data = $this->calculateTotalPrice($price_per_night, $checkIn, $checkOut);
                } else {
                    throw new \Exception('This property is not allowed to be rented as a room.');
                }
            } else {
                $price_per_night = $property->price_per_night;
                $pricing_data = $this->calculateTotalPrice($price_per_night, $checkIn, $checkOut);
            }
            return $pricing_data;
        } catch (\Exception $e) {
            Log::error('Error calculating booking price:' . $e->getMessage());
            throw new \Exception('Error calculating booking price.');
        }
    }

    public function createBooking($request)
    {

        try {
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            $this->validateRoomAndProperty($request->property_id, $request->room_id ?? null);
            $isAvailable = $this->checkPropertyOrRoomAvailabilityDates($request->property_id, $request->room_id ?? null, $checkIn, $checkOut);
            if (!$isAvailable) {
                throw new \Exception('This unit is not available for booking for the selected dates.');
            }

            // Calculate the total price
            $this->calculateBookingPrice($request->property_id, $request->room_id ?? null, $checkIn, $checkOut);

        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            throw new \Exception('Error creating booking: ' . $e->getMessage());
        }
    }



    protected function validateRoomAndProperty($property_id, $room_id = null)
    {
        $property = Property::findOrFail($property_id);
        if ($property->rent_type == 'room' && !$room_id) {
            throw new \Exception('Room selection is required for room rentals.');
        }
        if ($room_id) {
            $room = Room::findOrFail($room_id);
            ($room->property_id !== $property_id) ? throw new \Exception('Room does not belong to this property.') : null;
        }
    }
    protected function checkPropertyOrRoomAvailabilityDates($property_id, $room_id = null, Carbon $checkIn, Carbon $checkOut)
    {
        $property = Property::findOrFail($property_id);
        if ($property->rent_type == 'room' && $room_id) {
            $isAvailable = !Booking::where('room_id', $room_id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut]);
                })->exists();
        } else if ($property->rent_type == 'property' && !$room_id) {
            $isAvailable = !Booking::where('property_id', $property_id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut]);
                })->exists();
        } else {
            throw new \Exception('Booking: Error checking unit availability.');
        }
        if (!$isAvailable) {
            false;
        }
        return true;
    }



    protected function calculateTotalPrice($price_per_night, $checkIn, $checkOut)
    {
        $checkInFormatted = Carbon::parse($checkIn);
        $checkOutFormatted = Carbon::parse($checkOut);
        $nights = $checkInFormatted->diffInDays($checkOutFormatted);

        $basePrice = $price_per_night * $nights;
        $serviceFee = $basePrice * self::SERVICE_FEE_RATE;
        $priceWithServiceFee = $basePrice + $serviceFee;
        $taxes = $priceWithServiceFee * self::TAX_RATE;
        $totalPrice = $priceWithServiceFee + $taxes + self::MOVE_IN_PROTECTION_PRICE;

        $data = [
            'total_price' => $totalPrice,
            'move_in_protection_price' => self::MOVE_IN_PROTECTION_PRICE,
            'taxes' => $taxes,
            'service_fee' => $serviceFee
        ];
        return $data;
    }
}