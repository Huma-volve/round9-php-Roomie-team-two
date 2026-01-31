<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadIdDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'id_type' => 'required|in:national_id,passport,driving_license'
        ];
    }

    public function messages(): array
    {
        return [
            'id_document.required' => 'مستند الهوية مطلوب',
            'id_document.file' => 'يجب أن يكون مستند الهوية ملف',
            'id_document.mimes' => 'مستند الهوية يجب أن يكون من نوع: jpg, jpeg, png, pdf',
            'id_document.max' => 'حجم مستند الهوية يجب ألا يتجاوز 5 ميجا',
            'id_type.required' => 'نوع الهوية مطلوب',
            'id_type.in' => 'نوع الهوية يجب أن يكون: بطاقة شخصية، جواز سفر، أو رخصة قيادة',
        ];
    }
}