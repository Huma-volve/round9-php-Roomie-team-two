<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'phone' => ['required', 'string', 'max:20'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'special_request' => ['nullable', 'string', 'max:1000'],
            'move_in_protection' => ['boolean'],
            'guests' => ['required', 'array', 'min:1'],
            'guests.*.first_name' => ['required', 'string', 'max:255'],
            'guests.*.last_name' => ['required', 'string', 'max:255'],
            'guests.*.email' => ['nullable', 'email'],
            'guests.*.phone' => ['nullable', 'string', 'max:20'],

        ];
    }
    public function messages()
    {
        return [
            'guests.required' => 'At least one guest is required.',
            'check_in.after_or_equal' => 'Check-in date must be today or later.',
            'check_out.after' => 'Check-out date must be after the check-in date.',
        ];
    }
}
