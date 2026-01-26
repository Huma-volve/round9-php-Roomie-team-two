<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;

class SearchServices
{

    public function search(Request $request)
    {
        $query = Room::join('properties', 'rooms.property_id', '=', 'properties.id')
            ->leftJoin('property_images', function ($join) {
                $join->on('property_images.property_id', '=', 'properties.id')
                    ->where('property_images.is_main', true);
            });

        // 1) Property Type
        if ($request->property_type) {
            $query->where('properties.rent_type', $request->property_type);
        }

        // 2) BHK ()
        if ($request->bhk) {
            $query->where('properties.num_rooms', $request->bhk);
        }

        // 3) Budget
        if ($request->minBudget) {
            $query->where('rooms.price_per_month', '>=', $request->minBudget);
        }

        if ($request->maxBudget) {
            $query->where('rooms.price_per_month', '<=', $request->maxBudget);
        }

        // 4) Locality
        if ($request->locality) {
            $query->where('properties.title', 'LIKE', "%{$request->locality}%")
                ->orWhere('properties.description', 'LIKE', "%{$request->locality}%");
        }

        // SELECT OUTPUT
        $data = $query->select(
            'rooms.id',
            'rooms.room_number',
            'rooms.room_type',
            'rooms.price_per_month',
            'rooms.capacity',
            'rooms.room_bed_type',
            'properties.title',
            'properties.price_per_month',
            'properties.description',
            'properties.gender_preference',
            'property_images.image_path'
        )->paginate(20);

        return $data;
    }
}
