<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLifestyleTraitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'traits' => 'nullable|array',
            'early_bird' => 'required|boolean',
            'smoker' => 'required|boolean',
            'pets' => 'nullable|string|max:255',
            'work_from_home' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'traits.array' => 'الصفات يجب أن تكون مصفوفة',
            'early_bird.required' => 'حقل الاستيقاظ المبكر مطلوب',
            'early_bird.boolean' => 'حقل الاستيقاظ المبكر يجب أن يكون true أو false',
            'smoker.required' => 'حقل التدخين مطلوب',
            'smoker.boolean' => 'حقل التدخين يجب أن يكون true أو false',
            'pets.max' => 'وصف الحيوانات الأليفة لا يمكن أن يتجاوز 255 حرف',
            'work_from_home.required' => 'حقل العمل من المنزل مطلوب',
            'work_from_home.boolean' => 'حقل العمل من المنزل يجب أن يكون true أو false',
        ];
    }
}