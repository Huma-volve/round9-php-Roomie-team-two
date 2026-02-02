<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicInfoRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'aboutme' => 'nullable|string',
            'address'=> 'nullable|string',
             'max_budget' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'الاسم يجب أن يكون نص',
            'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرف',
            'gender.in' => 'الجنس يجب أن يكون male أو female',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجا',
             'max_budget.required' => 'الحد الأقصى للميزانية مطلوب',
            'max_budget.integer' => 'الميزانية يجب أن تكون رقم صحيح',
            'max_budget.min' => 'الميزانية يجب أن تكون صفر أو أكثر',
        ];
    }
}