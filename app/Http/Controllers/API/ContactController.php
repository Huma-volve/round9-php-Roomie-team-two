<?php

namespace App\Http\Controllers\Api;

use App\Events\ContactMessageReceived;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        // Rate limiting - منع الإرسال المتكرر
        $key = 'contact-message:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'لقد أرسلت عدد كبير من الرسائل. يرجى المحاولة لاحقاً.'
            ], 429);
        }

        try {
            // حفظ الرسالة
            $message = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
                'ip_address' => $request->ip()
            ]);

            // 🔥 إطلاق Event لإرسال الإشعار للأدمن
            event(new ContactMessageReceived($message));

            // تسجيل محاولة الإرسال
            RateLimiter::hit($key, 300); // 5 دقائق

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.'
            ], 201);

        } catch (\Exception $e) {
            // 🔍 طباعة تفاصيل الخطأ في الـ Log
            Log::error('Contact Form Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // 🔍 طباعة الخطأ مباشرة (للتطوير فقط)
            \Log::debug($e);
            
            // ⚠️ للتطوير فقط: إرجاع تفاصيل الخطأ في الـ Response
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.',
                'error' => config('app.debug') ? $e->getMessage() : null, // يظهر فقط في وضع التطوير
                'details' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null
            ], 500);
        }
    }
}