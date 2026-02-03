<?php

namespace App\Services\Admin;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyService
{
    public function getAll()
    {
        $properties = Property::select(
            'title',
            'description',
            'rent_type',
            'price_per_night',
            'num_rooms',
            'num_bathrooms',
            'max_guests',
            'gender_preference',
            'furnishing',
            'stay_minimum_in_days',
            'deposit',
            'unit_amenities',
            'lifestyle',
            'status',
            'latitude',
            'longitude',
            'available_from'
        )->paginate(10);
        return $properties;
    }

    public function storeProperty($request)
    {
        return DB::transaction(function () use ($request) {

            $property = Property::create([
                'admin_id' => 1, //auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'rent_type' => $request->rent_type,
                'price_per_night' => $request->price_per_night,
                'num_rooms' => $request->num_rooms,
                'num_bathrooms' => $request->num_bathrooms,
                'max_guests' => $request->max_guests,
                'gender_preference' => $request->gender_preference,
                'furnishing' => $request->furnishing,
                'stay_minimum_in_days' => 1,
                'deposit' => $request->deposit,
                'unit_amenities' => $request->unit_amenities,
                'lifestyle' => $request->lifestyle,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'rating' => $request->rating ?? 0,
                'available_from' => now(),
            ]);

            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainName = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
                $mainImage->storeAs('properties/main', $mainName, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => 'properties/main/' . $mainName,
                    'is_main' => 1
                ]);
            }

            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $img) {
                    $name = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                    $img->storeAs('properties/additional', $name, 'public');

                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_path' => 'properties/additional/' . $name,
                        'is_main' => 0
                    ]);
                }
            }

            return $property;
        });
    }

    public function getPropertyById($id)
    {
        return $property = Property::with('propertyImages')->findOrFail($id);
    }

    public function updateProperty($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $property = Property::with('propertyImages')->findOrFail($id);

            $property->update([
                'title' => $request['title'],
                'description' => $request['description'],
                'rent_type' => $request['rent_type'],
                'price_per_night' => $request['price_per_night'],
                'num_rooms' => $request['num_rooms'],
                'num_bathrooms' => $request['num_bathrooms'],
                'max_guests' => $request['max_guests'],
                'gender_preference' => $request['gender_preference'],
                'furnishing' => $request['furnishing'],
                'stay_minimum_in_days' => 1,
                'deposit' => $request['deposit'],
                'unit_amenities' => $request['unit_amenities'],
                'lifestyle' => $request['lifestyle'],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'status' => $request['status'],
                'rating' => $request['rating'] ?? 0,
            ]);

            if ($request->hasFile('main_image')) {
                $oldMain = $property->propertyImages->where('is_main', 1)->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->image_path);
                    $oldMain->delete();
                }

                $mainImage = $request->file('main_image');
                $mainName = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
                $mainImage->storeAs('properties/main', $mainName, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => 'properties/main/' . $mainName,
                    'is_main' => 1
                ]);
            }

            if ($request->hasFile('additional_images')) {
                $oldImages = $property->propertyImages->where('is_main', 0);
                foreach ($oldImages as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }

                foreach ($request->file('additional_images') as $img) {
                    $name = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                    $img->storeAs('properties/additional', $name, 'public');

                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_path' => 'properties/additional/' . $name,
                        'is_main' => 0
                    ]);
                }
            }

            return $property;
        });

        if ($request->hasFile('main_image')) {
            $oldMain = $property->propertyImages->where('is_main', 1)->first();
            if ($oldMain) {
                Storage::disk('public')->delete($oldMain->image_path);
                $oldMain->delete();
            }

            $mainImage = $request->file('main_image');
            $mainName = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
            $mainImage->storeAs('properties/main', $mainName, 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => 'properties/main/' . $mainName,
                'is_main' => 1
            ]);
        }

        if ($request->hasFile('additional_images')) {
            $oldImages = $property->propertyImages->where('is_main', 0);
            foreach ($oldImages as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }

            foreach ($request->file('additional_images') as $img) {
                $name = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->storeAs('properties/additional', $name, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => 'properties/additional/' . $name,
                    'is_main' => 0
                ]);
            }
        }
    }

    public function destroyByID($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();
        return true;
    }
}
