<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageStoreResource extends JsonResource
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
        'message' => $this->message_body,
        'sender'=> $this->sender->name,
        'conversation_id' => $this->conversation_id,
        'is_read' => $this->is_read ? true : false,
        ];
    }
}
