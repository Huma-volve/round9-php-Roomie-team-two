<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Models\AdminNotification;
use App\Models\User;

class SendNewReviewNotification
{
    /**
     * Handle the event.
     */
    public function handle(ReviewCreated $event): void
    {
        $review = $event->review;
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'new_review',
                'title' => 'مراجعة جديدة',
                'message' => "مراجعة جديدة {$review->rating} نجوم من {$review->user->first_name}",
                'notifiable_type' => get_class($review),
                'notifiable_id' => $review->id,
                'priority' => 'low',
                'action_url' => route('admin.reviews.show', $review->id),
                'data' => [
                    'review_id' => $review->id,
                    'rating' => $review->rating,
                    'user_name' => $review->user->first_name . ' ' . $review->user->last_name,
                    'property_id' => $review->property_id,
                ],
            ]);
        }
    }
}