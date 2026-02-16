<?php

namespace App\Http\Controllers;

use App\Events\ReviewCreated;
use App\Http\Requests\ReviewRequest;
use App\Models\Booking;
use App\Models\Review;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    use ApiResponseTrait;

    public function create($booking_id, ReviewRequest $request)
    {
        $booking = Booking::find($booking_id);

        if (!$booking) {
            return $this->errorResponse('Booking not found.');
        }

        if ($booking->check_out >= now()) {
            return $this->errorResponse('You cannot add a review until the stay has ended.');
        }

        // إنشاء المراجعة
        $review = Review::create([
            'user_id' => auth()->id(),
            'property_id' => $booking->property_id,
            'comment' => $request->comment,
            'rating' => $request->rating
        ]);
        // التحقق من عدم وجود مراجعة سابقة لنفس الـ Property
        $existingReview = Review::where('user_id', auth()->id())
            ->where('property_id', $booking->property_id)
            ->exists();

        // 🔥 إطلاق Event لإرسال إشعار للأدمن
        event(new ReviewCreated($review));

        return $this->successResponse('Review added successfully.', 201, $review);
    }

    /**
     * تحديث مراجعة
     */
    public function update($review_id, ReviewRequest $request)
    {
        $review = Review::where('id', $review_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$review) {
            return $this->errorResponse('Review not found or unauthorized.', 404);
        }

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment
        ]);

        return $this->successResponse('Review updated successfully.', 200, $review);
    }

    /**
     * حذف مراجعة
     */
    public function delete($review_id)
    {
        $review = Review::where('id', $review_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$review) {
            return $this->errorResponse('Review not found or unauthorized.', 404);
        }

        $review->delete();

        return $this->successResponse('Review deleted successfully.', 200);
    }

    /**
     * جلب مراجعات المستخدم
     */
    public function myReviews(Request $request)
    {
        $userId = auth()->id();

        $reviews = Review::where('user_id', $userId)
            ->with('property')
            ->latest()
            ->get();

        return $this->successResponse('User reviews fetched successfully.', 200, $reviews);
    }
}
