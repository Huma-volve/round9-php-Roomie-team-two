<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingCalcTotalPriceRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use ApiResponseTrait;
    private $bookingService;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function calculateTotalPrice(BookingCalcTotalPriceRequest $request)
    {
        $validated = $request->validated();
        try {
            $pricing_data = $this->bookingService->calculateBookingPrice($validated['property_id'], $validated['room_id'] ?? null, $validated['checkIn'], $validated['checkOut']);
            return $this->successResponse('Success', 200, $pricing_data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode(), null);
        }
    }

    public function store(StoreBookingRequest $request)
    {

        try {

            $this->bookingService->createBooking($request);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
