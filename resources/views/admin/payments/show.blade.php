{{-- @extends('layouts.admin')

@section('title', 'Payment Details - Roomie Admin')

@section('content')
    <header class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Payment Details</h2>
                <p class="text-gray-500 mt-1">{{ $payment->reservation_code }}</p>
            </div>
            <a href="{{ route('admin.payments.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                ← Back to Payments
            </a>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Payment Status</h3>
                    @if ($payment->payment_status === 'pending')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 rounded-full bg-yellow-600"></span>
                            Pending
                        </span>
                    @elseif ($payment->payment_status === 'paid')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 rounded-full bg-green-600"></span>
                            Paid
                        </span>
                    @elseif ($payment->payment_status === 'partially_paid')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Partially Paid
                        </span>
                    @elseif ($payment->payment_status === 'failed')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <span class="w-2 h-2 rounded-full bg-red-600"></span>
                            Failed
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <span class="w-2 h-2 rounded-full bg-gray-600"></span>
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Payment Type</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium">Payment Method</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ ucfirst($payment->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Booking Information</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-gray-600 text-sm">Booking ID</p>
                            <p class="text-lg font-semibold text-gray-900">#{{ $payment->booking->id }}</p>
                        </div>
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}"
                            class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                            View Booking
                        </a>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-sm font-medium">Property</p>
                            <p class="font-semibold text-gray-900 mt-1">{{ $payment->booking->property->title }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-sm font-medium">Check-in</p>
                            <p class="font-semibold text-gray-900 mt-1">{{ $payment->booking->check_in->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-sm font-medium">Guest Name</p>
                            <p class="font-semibold text-gray-900 mt-1">{{ $payment->booking->user->name }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-sm font-medium">Check-out</p>
                            <p class="font-semibold text-gray-900 mt-1">
                                {{ $payment->booking->check_out->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pricing Information</h3>

                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Total Amount</span>
                        <span class="font-semibold text-gray-900">${{ number_format($payment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Amount Paid</span>
                        <span class="font-semibold text-green-600">${{ number_format($payment->amount_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Amount Remaining</span>
                        <span class="font-semibold text-red-600">${{ number_format($payment->amount_remaining, 2) }}</span>
                    </div>
                </div>

                @if ($payment->paid_at)
                    <div class="mt-4 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-700">
                            <strong>Paid on:</strong> {{ $payment->paid_at->format('M d, Y \a\t H:i A') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Personal Details -->
            @if ($payment->personalDetails)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Personal Details</h3>

                    <div class="space-y-2 text-gray-700">
                        <p><strong>{{ $payment->personalDetails->address_line }}</strong></p>
                        <p>{{ $payment->personalDetails->city }}{{ $payment->personalDetails->state ? ', ' . $payment->personalDetails->state : '' }}
                        </p>
                        @if ($payment->personalDetails->postal_code)
                            <p>{{ $payment->personalDetails->postal_code }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Installment Schedules -->
            @if ($payment->installmentSchedules->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Installment Schedule</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-gray-200">
                                <tr>
                                    <th class="py-3 font-semibold text-gray-700">Installment</th>
                                    <th class="py-3 font-semibold text-gray-700">Amount</th>
                                    <th class="py-3 font-semibold text-gray-700">Due Date</th>
                                    <th class="py-3 font-semibold text-gray-700">Status</th>
                                    <th class="py-3 font-semibold text-gray-700">Paid Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($payment->installmentSchedules as $installment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 font-medium">Installment {{ $installment->installment_number }}
                                        </td>
                                        <td class="py-3">${{ number_format($installment->amount, 2) }}</td>
                                        <td class="py-3">{{ $installment->due_date->format('M d, Y') }}</td>
                                        <td class="py-3">
                                            @if ($installment->status === 'pending')
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif ($installment->status === 'paid')
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                            @elseif ($installment->status === 'overdue')
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                                            @else
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($installment->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @if ($installment->paid_at)
                                                {{ $installment->paid_at->format('M d, Y') }}
                                            @else
                                                <span class="text-gray-500">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Stripe Information -->
            @if ($payment->stripe_payment_intent_id)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Payment Gateway Information</h3>

                    <div class="space-y-3 text-sm">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 mb-1">Stripe Payment Intent ID</p>
                            <p class="font-mono font-semibold text-gray-900 break-all">
                                {{ $payment->stripe_payment_intent_id }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Summary</h3>

                <div class="space-y-4">
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <p class="text-indigo-600 text-sm font-medium">Reservation Code</p>
                        <p class="font-mono font-bold text-indigo-900 mt-1">{{ $payment->reservation_code }}</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-semibold text-gray-900">${{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Paid:</span>
                            <span
                                class="font-semibold text-green-600">${{ number_format($payment->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-gray-200 pt-2">
                            <span class="text-gray-600">Remaining:</span>
                            <span
                                class="font-semibold text-red-600">${{ number_format($payment->amount_remaining, 2) }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Payment Progress</span>
                            <span>{{ round(($payment->amount_paid / $payment->amount) * 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full"
                                style="width: {{ round(($payment->amount_paid / $payment->amount) * 100) }}%"></div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}"
                            class="block w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors text-center">
                            View Related Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}


@extends('layouts.admin')

@section('title', 'Payment Details - Roomie Admin')

@section('content')
    <header class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.payments.index') }}"
                    class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Payment Details</h2>
                    <p class="text-gray-500 mt-1">{{ $payment->reservation_code }}</p>
                </div>
            </div>

            <!-- Status Badge -->
            <div>
                @switch($payment->payment_status)
                    @case('paid')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-green-100 text-green-800">
                            ✓ Paid
                        </span>
                    @break

                    @case('partially_paid')
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-orange-100 text-orange-800">
                            ⚠ Partially Paid
                        </span>
                    @break

                    @case('pending')
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-yellow-100 text-yellow-800">
                            ⏳ Pending
                        </span>
                    @break

                    @case('failed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-red-100 text-red-800">
                            ✗ Failed
                        </span>
                    @break

                    @case('refunded')
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-purple-100 text-purple-800">
                            ↶ Refunded
                        </span>
                    @break
                @endswitch
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Overview -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Payment Overview</h3>
                <div class="grid grid-cols-2 gap-6">
                    <!-- Reservation Code -->
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Reservation Code</p>
                        <p class="text-lg font-semibold text-indigo-600 mt-1">{{ $payment->reservation_code }}</p>
                    </div>
                    <!-- Payment Type -->
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Payment Type</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1 capitalize">
                            {{ str_replace('_', ' ', $payment->payment_type) }}
                        </p>
                    </div>
                    <!-- Payment Method -->
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Payment Method</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1 capitalize">{{ $payment->payment_method }}</p>
                    </div>
                    <!-- Created Date -->
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Created</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $payment->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Booking Information</h3>
                <div class="space-y-4">
                    <!-- Guest -->
                    <div class="flex items-center gap-3">
                        @if ($payment->booking->user->image)
                            <img src="{{ $payment->booking->user->image }}" alt=""
                                class="h-12 w-12 rounded-full object-cover border border-gray-200">
                        @else
                            <div
                                class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                {{ substr($payment->booking->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $payment->booking->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $payment->booking->user->email }}</p>
                        </div>
                    </div>

                    <!-- Property & Dates -->
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Booking Details</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Property</p>
                                <p class="font-medium text-gray-900">{{ $payment->booking->property->title }}</p>
                            </div>
                            @if ($payment->booking->room)
                                <div>
                                    <p class="text-xs text-gray-500">Room</p>
                                    <p class="font-medium text-gray-900">{{ $payment->booking->room->room_number }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs text-gray-500">Check-in</p>
                                <p class="font-medium text-gray-900">{{ $payment->booking->check_in->format('M d, Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Check-out</p>
                                <p class="font-medium text-gray-900">{{ $payment->booking->check_out->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            @if ($payment->personalDetails)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Personal Details</h3>
                    <div class="space-y-2 text-gray-700">
                        <p class="font-medium">{{ $payment->personalDetails->address_line }}</p>
                        <p>{{ $payment->personalDetails->city }}, {{ $payment->personalDetails->state }}</p>
                        <p>{{ $payment->personalDetails->postal_code }}</p>
                    </div>
                </div>
            @endif

            <!-- Installment Schedule -->
            @if ($payment->payment_type === 'partial' && $payment->installmentSchedules->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Installment Schedule</h3>
                    <div class="space-y-3">
                        @foreach ($payment->installmentSchedules as $installment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">Installment
                                            {{ $installment->installment_number }}</p>
                                        <p class="text-sm text-gray-500">Due:
                                            {{ $installment->due_date->format('M d, Y') }}</p>
                                    </div>
                                    @switch($installment->status)
                                        @case('paid')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        @break

                                        @case('pending')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @break

                                        @case('overdue')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                        @break

                                        @case('failed')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                Failed
                                            </span>
                                        @break
                                    @endswitch
                                </div>
                                <p class="text-lg font-bold text-gray-900">${{ number_format($installment->amount, 2) }}
                                </p>
                                @if ($installment->paid_at)
                                    <p class="text-xs text-gray-500 mt-2">Paid on:
                                        {{ $installment->paid_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Payment Summary</h3>
                <div class="space-y-4">
                    <!-- Total Amount -->
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Total Amount</span>
                        <span class="font-semibold text-gray-900">${{ number_format($payment->amount, 2) }}</span>
                    </div>

                    <!-- Amount Paid -->
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Amount Paid</span>
                        <span class="font-semibold text-green-600">${{ number_format($payment->amount_paid, 2) }}</span>
                    </div>

                    <!-- Amount Remaining -->
                    <div class="flex justify-between pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Amount Remaining</span>
                        <span class="font-bold text-{{ $payment->amount_remaining > 0 ? 'red' : 'green' }}-600">
                            ${{ number_format($payment->amount_remaining, 2) }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                style="width: {{ ($payment->amount_paid / $payment->amount) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center font-semibold">
                            {{ round(($payment->amount_paid / $payment->amount) * 100) }}% Paid</p>
                    </div>
                </div>
            </div>

            <!-- Payment Actions -->
            @if ($availableActions && count($availableActions) > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Actions</h3>
                    <div class="space-y-3">
                        @foreach ($availableActions as $action)
                            <form action="{{ route('admin.payments.' . $action['action'], $payment) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    {{ isset($action['confirm']) && $action['confirm'] ? 'onclick=return confirm(\'Are you sure?\')' : '' }}
                                    class="w-full px-4 py-3 {{ $action['class'] }} text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $action['label'] }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Status Change Dropdown -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Change Status</h3>
                <form action="{{ route('admin.payments.update-status', $payment) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="space-y-3">
                        <select name="payment_status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Select Status</option>
                            <option value="pending" {{ $payment->payment_status === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="partially_paid"
                                {{ $payment->payment_status === 'partially_paid' ? 'selected' : '' }}>Partially Paid
                            </option>
                            <option value="paid" {{ $payment->payment_status === 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="failed" {{ $payment->payment_status === 'failed' ? 'selected' : '' }}>Failed
                            </option>
                            <option value="refunded" {{ $payment->payment_status === 'refunded' ? 'selected' : '' }}>
                                Refunded</option>
                        </select>

                        <textarea name="notes" placeholder="Optional notes..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            rows="3"></textarea>

                        <button type="submit"
                            class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Information</h3>
                <div class="space-y-4 text-sm">
                    <!-- Paid Date -->
                    @if ($payment->paid_at)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Paid Date</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $payment->paid_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif

                    <!-- Due Date -->
                    @if ($payment->payment_due_date)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Due Date</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $payment->payment_due_date->format('M d, Y') }}
                            </p>
                        </div>
                    @endif

                    <!-- Stripe Intent -->
                    @if ($payment->stripe_payment_intent_id)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Stripe Intent ID</p>
                            <p class="text-xs font-mono text-gray-600 mt-1 break-all">
                                {{ $payment->stripe_payment_intent_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- View Booking -->
            <a href="{{ route('admin.bookings.show', $payment->booking) }}"
                class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors text-center flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
                View Booking
            </a>
        </div>
    </div>
@endsection
