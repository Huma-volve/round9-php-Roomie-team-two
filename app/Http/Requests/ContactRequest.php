<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:5000',
            'terms_agreed' => 'required|accepted'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'message.required' => 'الرسالة مطلوبة',
            'message.min' => 'الرسالة يجب أن تكون 10 أحرف على الأقل',
            'terms_agreed.accepted' => 'يجب الموافقة على الشروط'
        ];
    }
}