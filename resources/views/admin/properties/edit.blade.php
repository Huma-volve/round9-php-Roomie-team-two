@extends('layouts.admin')

@section('title', 'Edit Property - Roomie Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Edit Property</h2>
        <p class="text-gray-500 mt-1">Update property details and images.</p>
    </div>
    
    <a href="{{ route('admin.properties.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors shadow-sm">
        Cancel
    </a>
</header>

<div class="max-w-3xl mx-auto">
    <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('description', $property->description) }}</textarea>
            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Rent Type -->
        <div class="mb-6">
            <label for="rent_type" class="block text-sm font-medium text-gray-700 mb-2">Rent Type</label>
            <select name="rent_type" id="rent_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @foreach(App\Enums\RentType::cases() as $type)
                    <option value="{{ $type->value }}" {{ old('rent_type', $property->rent_type) == $type->value ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('rent_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Price per Night -->
        <div class="mb-6">
            <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">Price per Night</label>
            <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night', $property->price_per_night) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" step="0.01">
            @error('price_per_night')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Number of Rooms / Bathrooms / Guests -->
        <div class="grid grid-cols-3 gap-6 mb-6">
            <div>
                <label for="num_rooms" class="block text-sm font-medium text-gray-700 mb-2">Rooms</label>
                <input type="number" name="num_rooms" id="num_rooms" value="{{ old('num_rooms', $property->num_rooms) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('num_rooms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="num_bathrooms" class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                <input type="number" name="num_bathrooms" id="num_bathrooms" value="{{ old('num_bathrooms', $property->num_bathrooms) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('num_bathrooms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="max_guests" class="block text-sm font-medium text-gray-700 mb-2">Max Guests</label>
                <input type="number" name="max_guests" id="max_guests" value="{{ old('max_guests', $property->max_guests) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('max_guests')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Gender Preference -->
        <div class="mb-6">
            <label for="gender_preference" class="block text-sm font-medium text-gray-700 mb-2">Gender Preference</label>
            <select name="gender_preference" id="gender_preference" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @foreach(App\Enums\GenderPreference::cases() as $gender)
                    <option value="{{ $gender->value }}" {{ old('gender_preference', $property->gender_preference) == $gender->value ? 'selected' : '' }}>
                        {{ $gender->name }}
                    </option>
                @endforeach
            </select>
            @error('gender_preference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Furnishing -->
        <div class="mb-6">
            <label for="furnishing" class="block text-sm font-medium text-gray-700 mb-2">Furnishing</label>
            <select name="furnishing" id="furnishing" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @foreach(App\Enums\Furnishing::cases() as $furnishing)
                    <option value="{{ $furnishing->value }}" {{ old('furnishing', $property->furnishing) == $furnishing->value ? 'selected' : '' }}>
                        {{ $furnishing->name }}
                    </option>
                @endforeach
            </select>
            @error('furnishing')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Deposit, Amenities, Lifestyle -->
        <div class="mb-6">
            <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Deposit</label>
            <input type="text" name="deposit" id="deposit" value="{{ old('deposit', $property->deposit) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('deposit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="unit_amenities" class="block text-sm font-medium text-gray-700 mb-2">Unit Amenities</label>
            <input type="text" name="unit_amenities" id="unit_amenities" value="{{ old('unit_amenities', $property->unit_amenities) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('unit_amenities')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label for="lifestyle" class="block text-sm font-medium text-gray-700 mb-2">Lifestyle</label>
            <input type="text" name="lifestyle" id="lifestyle" value="{{ old('lifestyle', $property->lifestyle) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            @error('lifestyle')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Latitude / Longitude -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('latitude')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('longitude')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Status / Rating -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(App\Enums\Status::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status', $property->status) == $status->value ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <input type="number" name="rating" id="rating" value="{{ old('rating', $property->rating) }}" min="0" max="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('rating')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Main Image -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
            @if($property->mainImage)
                <img src="{{ asset('storage/'.$property->mainImage->image_path) }}" alt="Main Image" class="h-24 mb-2">
            @endif
            <input type="file" name="main_image" class="w-full">
            @error('main_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Additional Images -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
            <div class="flex gap-2 mb-2">
                @foreach($property->propertyImages as $img)
                    <img src="{{ asset('storage/'.$img->image_path) }}" class="h-16">
                @endforeach
            </div>
            <input type="file" name="additional_images[]" multiple class="w-full">
            @error('additional_images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-500/20">
                Update Property
            </button>
        </div>
    </form>
</div>
@endsection
