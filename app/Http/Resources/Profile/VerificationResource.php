<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
           
            'email_verified' => $this->email_verified,
            'phone_verified' => $this->phone_verified,
            'id_verified' => $this->id_verified,
            'id_type' => $this->id_type,
            'rejection_reason' => $this->rejection_reason,
        ];
    }
}