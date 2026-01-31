<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'property_type' => 'nullable|in:room,apartment',
            'bhk' => 'nullable|integer|min:1|max:10',
            'min_budget' => 'nullable|numeric|min:0',
            'max_budget' => 'nullable|numeric|min:0|gte:min_budget',
            'locality' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius_km' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'property_type.in' => 'Property type must be either room or apartment.',
            'bhk.integer' => 'BHK must be a valid number.',
            'bhk.min' => 'BHK must be at least 1.',
            'bhk.max' => 'BHK cannot exceed 10.',
            'min_budget.numeric' => 'Minimum budget must be a valid number.',
            'min_budget.min' => 'Minimum budget cannot be negative.',
            'max_budget.numeric' => 'Maximum budget must be a valid number.',
            'max_budget.min' => 'Maximum budget cannot be negative.',
            'max_budget.gte' => 'Maximum budget must be greater than or equal to minimum budget.',
            'locality.string' => 'Locality must be a valid text.',
            'locality.max' => 'Locality cannot exceed 255 characters.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'radius_km.integer' => 'Radius must be a valid number.',
            'radius_km.min' => 'Radius must be at least 1 km.',
            'radius_km.max' => 'Radius cannot exceed 100 km.',
        ];
    }
}
