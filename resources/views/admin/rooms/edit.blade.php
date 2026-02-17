@extends('layouts.admin')

@section('title', 'Edit Room - Roomie Admin')

@section('content')
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Edit Room</h2>
            <p class="text-gray-500 mt-1">Update room details.</p>
        </div>
        
        <a href="{{ route('admin.rooms.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors shadow-sm">
            Cancel
        </a>
    </header>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Property -->
            <div class="mb-6">
                <label for="property_id" class="block text-sm font-medium text-gray-700 mb-2">Property</label>
                <select name="property_id" id="property_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ old('property_id', $room->property_id) == $property->id ? 'selected' : '' }}>
                            {{ $property->title }}
                        </option>
                    @endforeach
                </select>
                @error('property_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Room Number -->
            <div class="mb-6">
                <label for="room_number" class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
                <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('room_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Room Type -->
            <div class="mb-6">
                <label for="room_type" class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                <select name="room_type" id="room_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(App\Enums\RoomType::cases() as $type)
                        <option value="{{ $type->value }}" {{ old('room_type', $room->room_type) == $type->value ? 'selected' : '' }}>
                            {{ ucfirst($type->value) }}
                        </option>
                    @endforeach
                </select>
                @error('room_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price Per Night -->
            <div class="mb-6">
                <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">Price per Night</label>
                <input type="number" step="0.01" name="price_per_night" id="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('price_per_night')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Number of Beds -->
            <div class="mb-6">
                <label for="num_beds" class="block text-sm font-medium text-gray-700 mb-2">Number of Beds</label>
                <input type="number" name="num_beds" id="num_beds" value="{{ old('num_beds', $room->num_beds) }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('num_beds')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bed Type -->
            <div class="mb-6">
                <label for="room_bed_type" class="block text-sm font-medium text-gray-700 mb-2">Bed Type</label>
                <select name="room_bed_type" id="room_bed_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(App\Enums\RoomBedType::cases() as $bed)
                        <option value="{{ $bed->value }}" {{ old('room_bed_type', $room->room_bed_type) == $bed->value ? 'selected' : '' }}>
                            {{ ucfirst($bed->value) }}
                        </option>
                    @endforeach
                </select>
                @error('room_bed_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Capacity -->
            <div class="mb-6">
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $room->capacity) }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @foreach(App\Enums\Status::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status', $room->status) == $status->value ? 'selected' : '' }}>
                            {{ ucfirst($status->value) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deposit -->
            <div class="mb-6">
                <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Deposit (Optional)</label>
                <input type="text" name="deposit" id="deposit" value="{{ old('deposit', $room->deposit) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('deposit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Minimum Stay -->
            <div class="mb-6">
                <label for="minimum_stay" class="block text-sm font-medium text-gray-700 mb-2">Minimum Stay (days)</label>
                <input type="number" name="minimum_stay" id="minimum_stay" value="{{ old('minimum_stay', $room->minimum_stay) }}" min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('minimum_stay')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


                                 <!-- Room Size -->
            <div class="mb-6">
             <label for="size_in_sq_m" class="block text-sm font-medium text-gray-700 mb-2">
                Room Size (m²)
                 </label>
                 <input 
                  type="number" 
                   step="0.1" 
                   min="1"
                   name="size_in_sq_m" 
                   id="size_in_sq_m" 
                   value="{{ old('size_in_sq_m', $room->size_in_sq_m) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                 >
                @error('size_in_sq_m')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror
            </div>


        <!-- Main Image -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
            @if($room->mainImage)
                <img src="{{ asset('storage/'.$room->mainImage->image_path) }}" alt="Main Image" class="h-24 mb-2">
            @endif
            <input type="file" name="main_image" class="w-full">
            @error('main_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Additional Images -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
            <div class="flex gap-2 mb-2">
                @foreach($room->roomImages as $img)
                    <img src="{{ asset('storage/'.$img->image_path) }}" class="h-16">
                @endforeach
            </div>
            <input type="file" name="additional_images[]" multiple class="w-full">
            @error('additional_images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

                    <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-500/20">
                    Update Room
                </button>
            </div>
        </form>
    </div>
@endsection
