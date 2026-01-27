<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'سبب الرفض مطلوب',
            'reason.string' => 'سبب الرفض يجب أن يكون نص',
        ];
    }
}