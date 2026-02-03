<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant' => $this->tenant->name,
            'admin' => $this->admin->name,
            'last_message' => $this->messages->last()->message_body,
            'last_message_at' => \Carbon\Carbon::parse($this->last_message_at)->diffForHumans()
        ];
    }
}
