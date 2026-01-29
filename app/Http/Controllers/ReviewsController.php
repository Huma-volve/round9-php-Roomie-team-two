<?php

namespace App\Http\Controllers;

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
        $booking = Booking::where('id', $booking_id)->where('check_out', '<', now())->first();
        if (!$booking) {
            return $this->errorResponse('You cannot add a review until the stay has ended.');
        }
        $data = [
            'user_id' => auth()->id(),
            'property_id' => $booking->property_id,
            'comment' => $request->comment,
            'rating' => $request->rating
        ];
        $review = Review::create($data);
        return $this->successResponse($message = 'Review added successfully.', $status = 201,  $review);
    }

    public function update($review_id, ReviewRequest $request)
    {
        $review = Review::find($review_id);

        if (!$review) {
            return $this->errorResponse('Review not found.', 404);
        }

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment
        ]);

        return $this->successResponse('Review updated successfully.', 200, $review);
    }

    public function delete($review_id)
    {
        $review = Review::find($review_id);

        if (!$review) {
            return $this->errorResponse('Review not found.', 404);
        }

        $review->delete();

        return $this->successResponse('Review deleted successfully.', 200);
    }


    public function myReviews(Request $request)
    {
        $userId = auth()->id();

        $reviews = Review::where('user_id', $userId)
            ->with('property')
            ->get();

        return $this->successResponse('User reviews fetched successfully.', 200, $reviews);
    }
}
