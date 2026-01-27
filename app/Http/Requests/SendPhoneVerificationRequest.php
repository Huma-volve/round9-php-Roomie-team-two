<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendPhoneVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:20'
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.max' => 'رقم الهاتف لا يمكن أن يتجاوز 20 حرف',
        ];
    }
}