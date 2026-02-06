@extends('layouts.admin')

@section('title', 'Add New Property - Roomie Admin')
@section('styles')
<!-- Leaflet CSS -->
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
  crossorigin=""
/>

<!-- Leaflet JavaScript -->
<script
  src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
  crossorigin=""
></script>
@endsection
@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Add New Property</h2>
        <p class="text-gray-500 mt-1">Create a new property listing with full details.</p>
    </div>
    
    <a href="{{ route('admin.properties.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors shadow-sm">
        Cancel
    </a>
</header>

<div class="max-w-3xl mx-auto">
    <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf

        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('description') }}</textarea>
            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Rent Type -->
        <div class="mb-6">
            <label for="rent_type" class="block text-sm font-medium text-gray-700 mb-2">Rent Type</label>
            <select name="rent_type" id="rent_type" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @foreach(\App\Enums\RentType::cases() as $type)
                    <option value="{{ $type->value }}" {{ old('rent_type') == $type->value ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('rent_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Price per Night -->
        <div class="mb-6">
            <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">Price Per Night</label>
            <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night') }}" min="0" step="0.01" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('price_per_night')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Number of Rooms & Bathrooms & Max Guests -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label for="num_rooms" class="block text-sm font-medium text-gray-700 mb-2">Rooms</label>
                <input type="number" name="num_rooms" id="num_rooms" value="{{ old('num_rooms') }}" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('num_rooms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="num_bathrooms" class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                <input type="number" name="num_bathrooms" id="num_bathrooms" value="{{ old('num_bathrooms') }}" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('num_bathrooms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="max_guests" class="block text-sm font-medium text-gray-700 mb-2">Max Guests</label>
                <input type="number" name="max_guests" id="max_guests" value="{{ old('max_guests') }}" min="1" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('max_guests')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Gender Preference & Furnishing -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label for="gender_preference" class="block text-sm font-medium text-gray-700 mb-2">Gender Preference</label>
                <select name="gender_preference" id="gender_preference" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(\App\Enums\GenderPreference::cases() as $gender)
                        <option value="{{ $gender->value }}" {{ old('gender_preference') == $gender->value ? 'selected' : '' }}>
                            {{ $gender->name }}
                        </option>
                    @endforeach
                </select>
                @error('gender_preference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="furnishing" class="block text-sm font-medium text-gray-700 mb-2">Furnishing</label>
                <select name="furnishing" id="furnishing" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(\App\Enums\Furnishing::cases() as $furnish)
                        <option value="{{ $furnish->value }}" {{ old('furnishing') == $furnish->value ? 'selected' : '' }}>
                            {{ $furnish->name }}
                        </option>
                    @endforeach
                </select>
                @error('furnishing')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Deposit & Amenities -->
        <div class="mb-6">
            <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Deposit (optional)</label>
            <input type="text" name="deposit" id="deposit" value="{{ old('deposit') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('deposit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="unit_amenities" class="block text-sm font-medium text-gray-700 mb-2">Unit Amenities (optional)</label>
            <textarea name="unit_amenities" id="unit_amenities" rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('unit_amenities') }}</textarea>
            @error('unit_amenities')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Lifestyle -->
        <div class="mb-6">
            <label for="lifestyle" class="block text-sm font-medium text-gray-700 mb-2">Lifestyle</label>
            <input type="text" name="lifestyle" id="lifestyle" value="{{ old('lifestyle') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('lifestyle')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
       {{-- الخريطه --}}
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Property Location</label>
    <div id="map" style="height: 400px;" class="mb-2 rounded-lg border"></div>

    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude ?? '') }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude ?? '') }}">
    @error('latitude') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    @error('longitude') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>



        <!-- Status & Rating -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(\App\Enums\Status::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <input type="number" name="rating" id="rating" value="{{ old('rating', 0) }}" min="0" max="5" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('rating')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Images -->
        <div class="mb-6">
            <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
            <input type="file" name="main_image" id="main_image" required accept="image/*"
                class="w-full text-sm text-gray-500">
            @error('main_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="additional_images" class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
            <input type="file" name="additional_images[]" id="additional_images" multiple accept="image/*"
                class="w-full text-sm text-gray-500">
            @error('additional_images')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            @error('additional_images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-500/20">
                Create Property
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const defaultLat = latInput.value ? parseFloat(latInput.value) : 30.0444; // القاهرة
    const defaultLng = lngInput.value ? parseFloat(lngInput.value) : 31.2357;

    // إنشاء الخريطة
    const map = L.map('map').setView([defaultLat, defaultLng], 13);

    // إضافة Tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // إضافة Marker قابل للسحب
    const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    // تحديث القيم عند سحب Marker
    marker.on('dragend', function() {
        const pos = marker.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
    });

    // تحديث Marker عند الضغط على الخريطة
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(8);
        lngInput.value = e.latlng.lng.toFixed(8);
    });
});
</script>
@endsection