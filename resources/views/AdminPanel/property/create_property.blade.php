<?php 
/*
print_r($rentTypes);

echo '<br>';
echo '<br>';

print_r($genderTypes);
echo '<br>';

echo '<br>';
print_r($furnishingTypes);
echo '<br>';

echo '<br>';print_r($statusTypes);
echo '<br>';echo '<br>';

*/

?> 


<br><br><br><br><br><br>

<form action="{{ isset($property) ? route('admin.properties.update', $property->id) : route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($property))
        @method('PUT')
    @endif

    <!-- Title, Description ... باقي الحقول هنا كما هي ... -->

    <!-- Main Image -->
    <div class="mb-3">
        <label for="main_image" class="form-label">Main Image</label>
        <input type="file" name="main_image" id="main_image" accept="image/*">
        @if(isset($property) && $property->main_image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $property->main_image) }}" alt="Main Image" width="150">
            </div>
        @endif
        @error('main_image') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Additional Images -->
    <div class="mb-3">
        <label for="additional_images" class="form-label">Additional Images</label>
        <input type="file" name="additional_images[]" id="additional_images" multiple accept="image/*">
        @if(isset($property) && $property->propertyImages)
            <div class="mt-2 d-flex flex-wrap gap-2">
                @foreach($property->propertyImages as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="Additional Image" width="100">
                @endforeach
            </div>
        @endif
        @error('additional_images.*') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Title -->
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $property->title ?? '') }}">
        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control">{{ old('description', $property->description ?? '') }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Rent Type -->
    <div class="mb-3">
        <label for="rent_type" class="form-label">Rent Type</label>
        <select name="rent_type" id="rent_type" class="form-control">
            @foreach(\App\Enums\RentType::options() as $key => $label)
                <option value="{{ $key }}" {{ old('rent_type', $property->rent_type ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('rent_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Price per night -->
    <div class="mb-3">
        <label for="price_per_night" class="form-label">Price per Night</label>
        <input type="number" name="price_per_night" id="price_per_night" step="0.01" class="form-control" value="{{ old('price_per_night', $property->price_per_night ?? '') }}">
        @error('price_per_night') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Number of Rooms -->
    <div class="mb-3">
        <label for="num_rooms" class="form-label">Number of Rooms</label>
        <input type="number" name="num_rooms" id="num_rooms" class="form-control" value="{{ old('num_rooms', $property->num_rooms ?? 0) }}">
        @error('num_rooms') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Number of Bathrooms -->
    <div class="mb-3">
        <label for="num_bathrooms" class="form-label">Number of Bathrooms</label>
        <input type="number" name="num_bathrooms" id="num_bathrooms" class="form-control" value="{{ old('num_bathrooms', $property->num_bathrooms ?? 0) }}">
        @error('num_bathrooms') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Max Guests -->
    <div class="mb-3">
        <label for="max_guests" class="form-label">Maximum Guests</label>
        <input type="number" name="max_guests" id="max_guests" class="form-control" value="{{ old('max_guests', $property->max_guests ?? 1) }}">
        @error('max_guests') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Gender Preference -->
    <div class="mb-3">
        <label for="gender_preference" class="form-label">Gender Preference</label>
        <select name="gender_preference" id="gender_preference" class="form-control">
            @foreach(\App\Enums\GenderPreference::options() as $key => $label)
                <option value="{{ $key }}" {{ old('gender_preference', $property->gender_preference ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('gender_preference') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Furnishing -->
    <div class="mb-3">
        <label for="furnishing" class="form-label">Furnishing</label>
        <select name="furnishing" id="furnishing" class="form-control">
            @foreach(\App\Enums\Furnishing::options() as $key => $label)
                <option value="{{ $key }}" {{ old('furnishing', $property->furnishing ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('furnishing') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Deposit -->
    <div class="mb-3">
        <label for="deposit" class="form-label">Deposit</label>
        <input type="text" name="deposit" id="deposit" class="form-control" value="{{ old('deposit', $property->deposit ?? '') }}">
        @error('deposit') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Unit Amenities -->
    <div class="mb-3">
        <label for="unit_amenities" class="form-label">Unit Amenities</label>
        <textarea name="unit_amenities" id="unit_amenities" class="form-control">{{ old('unit_amenities', $property->unit_amenities ?? '') }}</textarea>
        @error('unit_amenities') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Lifestyle -->
    <div class="mb-3">
        <label for="lifestyle" class="form-label">Lifestyle</label>
        <textarea name="lifestyle" id="lifestyle" class="form-control">{{ old('lifestyle', $property->lifestyle ?? '') }}</textarea>
        @error('lifestyle') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Latitude -->
    <div class="mb-3">
        <label for="latitude" class="form-label">Latitude</label>
        <input type="number" name="latitude" id="latitude" step="0.00000001" class="form-control" value="{{ old('latitude', $property->latitude ?? '') }}">
        @error('latitude') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Longitude -->
    <div class="mb-3">
        <label for="longitude" class="form-label">Longitude</label>
        <input type="number" name="longitude" id="longitude" step="0.00000001" class="form-control" value="{{ old('longitude', $property->longitude ?? '') }}">
        @error('longitude') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control">
            @foreach(\App\Enums\Status::options() as $key => $label)
                <option value="{{ $key }}" {{ old('status', $property->status ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <!-- Rating -->
    <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <input type="number" name="rating" id="rating" class="form-control" min="0" max="5" value="{{ old('rating', $property->rating ?? 0) }}">
        @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        {{ isset($property) ? 'Update Property' : 'Create Property' }}
    </button>
</form>
