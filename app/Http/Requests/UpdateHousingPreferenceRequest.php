<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHousingPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_location' => 'sometimes|required|string|max:255',
           
            'move_in_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'preferred_location.required' => 'الموقع المفضل مطلوب',
            'preferred_location.max' => 'الموقع المفضل لا يمكن أن يتجاوز 255 حرف',
            'max_budget.required' => 'الحد الأقصى للميزانية مطلوب',
            'max_budget.integer' => 'الميزانية يجب أن تكون رقم صحيح',
            'max_budget.min' => 'الميزانية يجب أن تكون صفر أو أكثر',
            'move_in_date.date' => 'تاريخ الانتقال يجب أن يكون تاريخ صحيح',
        ];
    }
}