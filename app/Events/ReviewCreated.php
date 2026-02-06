<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * القنوات التي سيتم البث عليها
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notifications'),
        ];
    }

    /**
     * اسم الحدث الذي سيُبث
     */
    public function broadcastAs(): string
    {
        return 'review.created';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->review->id,
            'rating' => $this->review->rating,
            'user_name' => $this->review->user->name ?? 'مستخدم',
            'property_name' => $this->review->property->name ?? 'غير محدد',
            'comment' => $this->review->comment,
            'message' => 'تقييم جديد (' . $this->review->rating . ' نجوم) من ' . ($this->review->user->name ?? 'مستخدم'),
            'type' => 'review_created',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}