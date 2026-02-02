@if ($errors->any())
    <div style="background:#ffe6e6; padding:15px; margin-bottom:20px;">
        <strong>Validation Errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<br> <br> <br>
<form action="{{ isset($room)
    ? route('admin.rooms.update', $room->id)
    : route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @isset($room)
        @method('PUT')
    @endisset
     <div>
        <label>Main Image</label>
        <input type="file" name="main_image" accept="image/*">
        @if(isset($room) && $room->main_image)
            <img src="{{ asset('storage/' . $room->main_image) }}" width="150" alt="Main Image">
        @endif
        @error('main_image')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

     <div>
        <label>Additional Images</label>
        <input type="file" name="additional_images[]" accept="image/*" multiple>
        @if(isset($room) && $room->additional_images)
            @foreach($room->additional_images as $img)
                <img src="{{ asset('storage/' . $img) }}" width="100" alt="Additional Image">
            @endforeach
        @endif
        @error('additional_images.*')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Property</label>
        <select name="property_id" class="form-select" required>
            <option value="">-- Select Property --</option>

            @foreach ($properties as $property)
                <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                    {{ $property->title }}
                </option>
            @endforeach
        </select>

        @error('property_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- room_number --}}
    <div>
        <label>Room Number</label>
        <input type="text" name="room_number" value="{{ old('room_number', $room->room_number ?? '') }}">
    </div>

    {{-- room_type --}}
    <div>
        <label>Room Type</label>
        <select name="room_type">
            @foreach (['private', 'shared'] as $type)
                <option value="{{ $type }}" {{ old('room_type', $room->room_type ?? '') == $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- price_per_night --}}
    <div>
        <label>Price Per Night</label>
        <input type="number" step="0.01" name="price_per_night"
            value="{{ old('price_per_night', $room->price_per_night ?? '') }}">
    </div>

    {{-- num_beds --}}
    <div>
        <label>Number of Beds</label>
        <input type="number" name="num_beds" value="{{ old('num_beds', $room->num_beds ?? '') }}">
    </div>

    {{-- room_bed_type --}}
    <div>
        <label>Bed Type</label>
        <select name="room_bed_type">
            @foreach (['king', 'queen', 'single', 'double', 'triple', 'quad'] as $bed)
                <option value="{{ $bed }}" {{ old('room_bed_type', $room->room_bed_type ?? '') == $bed ? 'selected' : '' }}>
                    {{ ucfirst($bed) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- size_in_sq_m --}}
    <div>
        <label>Size (m²)</label>
        <input type="number" name="size_in_sq_m" value="{{ old('size_in_sq_m', $room->size_in_sq_m ?? '') }}">
    </div>

    {{-- capacity --}}
    <div>
        <label>Capacity</label>
        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity ?? '') }}">
    </div>

    {{-- current_roomates --}}
    <div>
        <label>Current Roommates</label>
        <input type="number" name="current_roomates"
            value="{{ old('current_roomates', $room->current_roomates ?? 0) }}">
    </div>

    {{-- room_amenities --}}
    <div>
        <label>Amenities</label>
        <input type="text" name="room_amenities" value="{{ old('room_amenities', $room->room_amenities ?? '') }}">
    </div>

    {{-- status --}}
    <div>
        <label>Status</label>
        <select name="status">
            @foreach (['available', 'unavailable'] as $status)
                <option value="{{ $status }}" {{ old('status', $room->status ?? '') == $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- available_from --}}
    <div>
        <label>Available From</label>
        <input type="date" name="available_from" value="{{ old('available_from', $room->available_from ?? '') }}">
    </div>

    {{-- deposit --}}
    <div>
        <label>Deposit</label>
        <input type="number" step="0.01" name="deposit" value="{{ old('deposit', $room->deposit ?? '') }}">
    </div>

    {{-- minimum_stay --}}
    <div>
        <label>Minimum Stay (days)</label>
        <input type="number" name="minimum_stay" value="{{ old('minimum_stay', $room->minimum_stay ?? '') }}">
    </div>

    <button type="submit">
        {{ isset($room) ? 'Update Room' : 'Create Room' }}
    </button>
</form>