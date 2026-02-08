<?php

namespace App\Services\Admin;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BookingService
{

    private const PAGINATE_COUNT = 10;
    /**
     * Applies filters to the booking query.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function applyFilters($request): array
    {
        try {
            // Build query with relationships
            $query = Booking::query()
                ->with(['user', 'property', 'room', 'guests', 'payment'])
                ->latest('created_at');

            // Filter by user
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by property
            if ($request->filled('property_id')) {
                $query->where('property_id', $request->property_id);
            }

            // Filter by booking status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by check-in date range
            if ($request->filled('check_in_from')) {
                $query->whereDate('check_in', '>=', $request->check_in_from);
            }

            if ($request->filled('check_in_to')) {
                $query->whereDate('check_in', '<=', $request->check_in_to);
            }

            // Search by property title or user name/email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('property', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Paginate results
            $bookings = $query->paginate(self::PAGINATE_COUNT);

            // Get filter options
            $users = User::where('is_admin', false)
                ->pluck('email', 'id')
                ->prepend('All Users', '');

            $properties = Property::pluck('title', 'id')
                ->prepend('All Properties', '');

            $statusOptions = [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'cancelled' => 'Cancelled',
                'completed' => 'Completed'
            ];

            return [
                'bookings' => $bookings,
                'users' => $users,
                'properties' => $properties,
                'statuses' => $statusOptions,
                'search' => $request->search ?? '',
                'selected_user' => $request->user_id ?? '',
                'selected_property' => $request->property_id ?? '',
                'selected_status' => $request->status ?? '',
                'check_in_from' => $request->check_in_from ?? '',
                'check_in_to' => $request->check_in_to ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Error applying booking filters: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Retrieve a booking with all its related data (user, property, room, guests, payment details).
     *
     * @param Booking $booking The booking to retrieve details for.
     * @return Booking The booking object with all its related data.
     * @throws \Exception If there is an error retrieving the booking details.
     */
    public function getBookingDetails(Booking $booking): Booking
    {
        try {
            return $booking->load(['user', 'property', 'room', 'guests', 'payment.installmentSchedules', 'payment.personalDetails']);
        } catch (\Exception $e) {
            Log::error('Error retrieving booking details: ' . $e->getMessage());
            throw $e;
        }
    }
}
