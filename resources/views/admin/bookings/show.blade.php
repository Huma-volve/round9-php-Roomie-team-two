@extends('layouts.admin')

@section('title', 'Booking Details - Roomie Admin')

@section('content')
    <header class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Booking Details</h2>
                <p class="text-gray-500 mt-1">#{{ $booking->id }}</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                ← Back to Bookings
            </a>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Status Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Booking Status</h3>
                    @if ($booking->status === 'pending')
                        <div class="flex gap-2">
                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                                    Confirm
                                </button>
                            </form>
                            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                                    Cancel
                                </button>
                            </form>
                        </div>
                    @elseif ($booking->status === 'confirmed')
                        <div class="flex gap-2">
                            <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                    Mark as Completed
                                </button>
                            </form>
                            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                                    Cancel
                                </button>
                            </form>
                        </div>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span
                                class="w-2 h-2 rounded-full {{ $booking->status === 'completed' ? 'bg-green-600' : 'bg-red-600' }}"></span>
                            {{ ucfirst($booking->status) }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Current Status</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ ucfirst($booking->status) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Booked On</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $booking->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Guest Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Guest Information</h3>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3 mb-4">
                        @if ($booking->user->image)
                            <img src="{{ $booking->user->image }}" alt=""
                                class="h-12 w-12 rounded-full object-cover border border-gray-200">
                        @else
                            <div
                                class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                {{ substr($booking->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $booking->user->name }}</h4>
                            <p class="text-gray-600 text-sm">{{ $booking->user->email }}</p>
                        </div>
                    </div>
                    <p class="text-gray-700"><strong>Phone:</strong> {{ $booking->phone }}</p>
                </div>

                @if ($booking->guests->count() > 0)
                    <h4 class="font-semibold text-gray-800 mb-3">Additional Guests</h4>
                    <div class="space-y-3">
                        @foreach ($booking->guests as $guest)
                            <div class="p-3 border border-gray-200 rounded-lg">
                                <p class="font-medium text-gray-900">{{ $guest->first_name }} {{ $guest->last_name }}</p>
                                @if ($guest->email)
                                    <p class="text-gray-600 text-sm">{{ $guest->email }}</p>
                                @endif
                                @if ($guest->phone)
                                    <p class="text-gray-600 text-sm">{{ $guest->phone }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Property & Room Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Property & Room Details</h3>

                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Property</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $booking->property->title }}</p>
                    </div>

                    @if ($booking->room)
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-600 text-sm font-medium">Room</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $booking->room->room_number }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-600 text-sm font-medium">Room Type</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">
                                    {{ ucfirst($booking->room->room_type) }}</p>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-sm font-medium">Booking Type</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">Entire Apartment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dates & Duration -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Check-in & Check-out</h3>

                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Check-in</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $booking->check_in->format('M d, Y') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Check-out</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $booking->check_out->format('M d, Y') }}</p>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <p class="text-indigo-600 text-sm font-medium">Duration</p>
                        <p class="text-lg font-semibold text-indigo-900 mt-1">{{ $booking->getNightsCount() }} nights</p>
                    </div>
                </div>
            </div>

            @if ($booking->special_request)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Special Requests</h3>
                    <p class="text-gray-700">{{ $booking->special_request }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pricing Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pricing Summary</h3>

                <div class="space-y-3 pb-4 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Price</span>
                        <span class="font-semibold text-gray-900">${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    @if ($booking->move_in_protection)
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">✓ Move-in Protection Included</p>
                        </div>
                    @endif
                </div>

                <!-- Payment Info -->
                @if ($booking->payment)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-800 mb-3">Payment Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Status</span>
                                <span class="font-semibold">
                                    @if ($booking->payment->payment_status === 'pending')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif ($booking->payment->payment_status === 'paid')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                    @elseif ($booking->payment->payment_status === 'partially_paid')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Partially
                                            Paid</span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ ucfirst($booking->payment->payment_status) }}</span>
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Type</span>
                                <span
                                    class="font-semibold">{{ ucfirst(str_replace('_', ' ', $booking->payment->payment_type)) }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-semibold">{{ ucfirst($booking->payment->payment_method) }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Amount Paid</span>
                                <span class="font-semibold">${{ number_format($booking->payment->amount_paid, 2) }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Amount Remaining</span>
                                <span
                                    class="font-semibold text-red-600">${{ number_format($booking->payment->amount_remaining, 2) }}</span>
                            </div>

                            @if ($booking->payment->reservation_code)
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Reservation Code</p>
                                    <p class="font-mono font-semibold text-gray-900">
                                        {{ $booking->payment->reservation_code }}</p>
                                </div>
                            @endif

                            <a href="{{ route('admin.payments.show', $booking->payment) }}"
                                class="block mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors text-center">
                                View Payment Details
                            </a>
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-700">No payment initiated yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
