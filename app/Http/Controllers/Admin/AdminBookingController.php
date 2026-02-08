<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Services\Admin\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    protected $bookingService;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function index(Request $request)
    {
        try {
            // Apply filters
            $data = $this->bookingService->applyFilters($request);

            return view('admin.bookings.index', $data);
        } catch (\Exception $e) {
            Log::error('Error loading bookings: ' . $e->getMessage());
            return back()->with('error', 'Error loading bookings');
        }
    }

    public function show(Booking $booking)
    {
        try {

            $booking = $this->bookingService->getBookingDetails($booking);
            return view('admin.bookings.show', compact('booking'));
        } catch (\Exception $e) {
            Log::error('Error loading booking details: ' . $e->getMessage());
            return back()->with('error', 'Error loading booking details');
        }
    }

    public function markAsConfirmed(Booking $booking)
    {
        try {
            if ($booking->status !== 'pending') {
                return back()->with('error', 'Only pending bookings can be confirmed');
            }
            $booking->status = 'confirmed';
            $booking->save();

            return back()->with('success', 'Booking confirmed successfully');
        } catch (\Exception $e) {
            Log::error('Error confirming booking: ' . $e->getMessage());
            return back()->with('error', 'Error confirming booking');
        }
    }

    public function markAsCompleted(Booking $booking)
    {
        try {
            if ($booking->status !== 'confirmed') {
                return back()->with('error', 'Only confirmed bookings can be marked as completed');
            }
            $booking->status = 'completed';
            $booking->save();

            return back()->with('success', 'Booking marked as completed successfully');
        } catch (\Exception $e) {
            Log::error('Error completing booking: ' . $e->getMessage());
            return back()->with('error', 'Error completing booking');
        }
    }

    public function cancel(Booking $booking)
    {
        try {
            if ($booking->status === 'cancelled') {
                return back()->with('error', 'Booking is already cancelled');
            }
            $booking->status = 'cancelled';
            $booking->save();

            return back()->with('success', 'Booking cancelled successfully');
        } catch (\Exception $e) {
            Log::error('Error cancelling booking: ' . $e->getMessage());
            return back()->with('error', 'Error cancelling booking');
        }
    }

    public function getUserBookings(User $user)
    {
        try {
            $bookings = $user->bookings()->with('property')->paginate(10);
            return view('admin.bookings.user-bookings', compact('bookings', 'user'));
        } catch (\Exception $e) {
            Log::error('Error loading user bookings: ' . $e->getMessage());
            return back()->with('error', 'Error loading user bookings');
        }
    }
}
