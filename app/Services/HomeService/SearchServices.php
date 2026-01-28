<?php

namespace App\Services\HomeService;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;

class SearchServices
{

    public function search(Request $request)
    {
        $query = Room::query()
            ->select([
                'id',
                'property_id',
                'room_number',
                'room_type',
                'price_per_night',
                'capacity',
                'room_bed_type',
                'created_at',
            ])
            ->with([
                'property:id,title,description,gender_preference',
                'property.mainImage:id,property_id,image_path',
            ]);

        // 1) Property Type
        if ($request->property_type) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('rent_type', $request->property_type);
            });
        }

        // 2) BHK
        if ($request->bhk) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('num_rooms', $request->bhk);
            });
        }

        // 3) Budget
        if ($request->minBudget) {
            $query->where('price_per_night', '>=', $request->minBudget);
        }

        if ($request->maxBudget) {
            $query->where('price_per_night', '<=', $request->maxBudget);
        }

        // 4) Locality
        if ($request->locality) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->locality}%")
                    ->orWhere('description', 'LIKE', "%{$request->locality}%");
            });
        }

        $data = $query->paginate(20);

        return $data;
    }
}
