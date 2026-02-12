<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'property' => [
                'id' => $this->property->id ?? null,
                'title' => $this->property->title ?? null,
                'address' => $this->property->address ?? null,
                'price' => $this->property->price ?? null,
                'image' => $this->property && $this->property->images && $this->property->images->first()
                    ? url('storage/' . $this->property->images->first()->image_url) 
                    : null, 
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}