<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HousingPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'preferred_location' => $this->preferred_location,
            'move_in_date' => $this->move_in_date->format('Y-m-d'),
        ];
    }
}