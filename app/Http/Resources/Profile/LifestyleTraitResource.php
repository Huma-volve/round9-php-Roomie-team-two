<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class LifestyleTraitResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'traits' => $this->traits,
            'early_bird' => $this->early_bird,
            'smoker' => $this->smoker,
            'pets' => $this->pets,
            'work_from_home' => $this->work_from_home,
        ];
    }
}