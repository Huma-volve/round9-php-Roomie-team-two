<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ✅ غيّر
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReviewCreated implements ShouldBroadcastNow // ✅ غيّر
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
        
        // ✅ إضافة Log
        Log::info('ReviewCreated event created', [
            'review_id' => $review->id,
            'rating' => $review->rating
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'review.created';
    }

    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->review->id,
            'rating' => $this->review->rating,
            'user_name' => $this->review->user->name ?? 'مستخدم',
            'property_name' => $this->review->property->name ?? 'غير محدد',
            'comment' => $this->review->comment,
            'message' => 'تقييم جديد (' . $this->review->rating . ' نجوم) من ' . ($this->review->user->name ?? 'مستخدم'),
            'type' => 'review_created',
            'created_at' => now()->toDateTimeString(),
        ];
        
        // ✅ إضافة Log
        Log::info('Broadcasting review data', $data);
        
        return $data;
    }
}