<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingCalcTotalPriceRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use ApiResponseTrait;
    private $bookingService;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function calculateTotalPrice(BookingCalcTotalPriceRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $pricing_data = $this->bookingService->calculateBookingPrice(
                $validated['property_id'],
                $validated['room_id'] ?? null,
                $validated['check_in'],
                $validated['check_out'],
                $validated['move_in_protection'] ?? false
            );

            return $this->successResponse('Price calculated successfully', 200, $pricing_data);
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                method_exists($e, 'getCode') && $e->getCode() ? $e->getCode() : 400,
                null
            );
        }
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking($request);

            return $this->successResponse(
                'Booking created successfully',
                201,
                $booking->load('guests', 'room', 'property')
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                method_exists($e, 'getCode') && $e->getCode() ? $e->getCode() : 400,
                null
            );
        }
    }

    /**
     * Get all user bookings
     */
    public function getUserBookings(): JsonResponse
    {
        try {
            $bookings = $this->bookingService->getUserBookings(auth()->id());
            return $this->successResponse('User bookings retrieved Successfully.', 200, $bookings);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400, null);
        }
    }

    /**
     * Get booking details
     */
    public function show(Booking $booking): JsonResponse
    {
        try {
            if ($booking->user_id !== auth()->id()) {
                return $this->errorResponse('Unauthorized', 403, null);
            }
            return $this->successResponse(
                'Booking details retrieved',
                200,
                $booking->load('user', 'room', 'property', 'guests', 'payment')
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400, null);
        }
    }

    /**
     * Cancel a booking
     * @param Booking $booking
     * @return JsonResponse
     */
    public function cancel(Booking $booking): JsonResponse
    {
        try {
            // Check authorization
            // if ($booking->user_id !== auth()->id()) {
            //     return $this->errorResponse('Unauthorized', 403, null);
            // }

            $this->bookingService->cancelBooking($booking);

            return $this->successResponse('Booking cancelled successfully', 200, $booking);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400, null);
        }
    }
}
