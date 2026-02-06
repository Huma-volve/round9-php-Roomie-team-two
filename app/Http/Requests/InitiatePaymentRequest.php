<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
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
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'payment_type' => ['required', 'in:full,partial,installment'],
            'payment_method' => ['required', 'in:card,klarna'],
            'stripe_payment_method_id' => ['nullable', 'string'], // required if payment_method is card
            'personal_details.address_line' => ['required', 'string'],
            'personal_details.city' => ['required', 'string', 'max:255'],
            'personal_details.state' => ['nullable', 'string', 'max:255'],
            'personal_details.postal_code' => ['nullable', 'string', 'max:10'],
        ];
    }
}
