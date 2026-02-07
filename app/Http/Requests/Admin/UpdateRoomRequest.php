<?php

namespace App\Http\Requests\Admin;

use App\Enums\RoomBedType;
use App\Enums\RoomType;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120', 

        'additional_images' => 'nullable|array',
        'additional_images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        
        'property_id' => 'required|exists:properties,id',

        'room_number' => 'required|string|max:50',

        'room_type' => ['required', new Enum(RoomType::class)],

        'price_per_night' => 'required|numeric|min:0',

        'num_beds' => 'required|integer|min:1',

        'room_bed_type' => ['required', new Enum(RoomBedType::class)],

        'size_in_sq_m' => 'nullable|numeric|min:1',

        'capacity' => 'required|integer|min:1',

        'current_roomates' => 'nullable|integer|min:0',

        'room_amenities' => 'nullable|string',
        'room_amenities.*' => 'string|max:50',

        'status' => ['required', new Enum(Status::class)],

        'deposit' => 'nullable|string|max:255',

        'minimum_stay' => 'nullable|integer|min:1',
        ];
    }
}
