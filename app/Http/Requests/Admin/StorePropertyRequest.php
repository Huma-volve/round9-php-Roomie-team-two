<?php

namespace App\Http\Requests\Admin;

use App\Enums\Furnishing;
use App\Enums\GenderPreference;
use App\Enums\RentType;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePropertyRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rent_type' => ['required', new Enum(RentType::class)],
            'price_per_night' => 'required|numeric|min:0',
            'num_rooms' => 'required|integer|min:0',
            'num_bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'gender_preference' => ['required', new Enum(GenderPreference::class)],
            'furnishing' => ['required', new Enum(Furnishing::class)],
            'deposit' => 'nullable|string|max:255',
            'unit_amenities' => 'nullable|string',
            'lifestyle' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', new Enum(Status::class)],
            'rating' => 'required|integer|min:0|max:5',

            // Images (main required for store)
            'main_image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',

        ];
    }
}
