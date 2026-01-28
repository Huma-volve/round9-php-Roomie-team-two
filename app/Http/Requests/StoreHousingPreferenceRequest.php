<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHousingPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_location' => 'required|string|max:255',
            
            'move_in_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'preferred_location.required' => 'الموقع المفضل مطلوب',
            'preferred_location.max' => 'الموقع المفضل لا يمكن أن يتجاوز 255 حرف',
            'move_in_date.date' => 'تاريخ الانتقال يجب أن يكون تاريخ صحيح',
        ];
    }
}