@extends('layouts.admin')

@section('title', 'Manage Users - Roomie Admin')

@section('content')
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Property Management</h2>
            <p class="text-gray-500 mt-1">View and manage all properties.</p>
        </div>

        <div class="flex items-center gap-4">
            <form action="{{ route('admin.properties.index') }}" method="GET" class="flex items-center gap-4">

                <div class="relative w-64">
                    <input type="text" name="search" placeholder="Search properties..." value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                        </svg>
                    </div>
                </div>

                <a href="{{ route('admin.properties.create') }}"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Property
                </a>
            </form>
        </div>
    </header>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Rent Type</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Price / Night</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Rooms</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Guests</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Rating</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($properties as $property)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $property->title }}</h3>
                                            <p class="text-gray-500 text-sm line-clamp-1">
                                                {{ Str::limit($property->description, 60) }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $property->rent_type }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ number_format($property->price_per_night) }} EGP
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        🛏 {{ $property->num_rooms }} /
                                        🚿 {{ $property->num_bathrooms }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $property->max_guests }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                                                                                                    {{ $property->status === 'available'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        ⭐ {{ $property->rating }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">

                                            <!-- Edit -->
                                            <a href="{{ route('admin.properties.edit', $property) }}"
                                                class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this property?');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>


                                </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection