<?php

namespace App\Services\Admin;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomService
{
    public function getAll()
    {
        $rooms = Room::with('property:id,title')->paginate(10);
        return $rooms;
    }

    public function storeRoom($request)
    {
        return DB::transaction(function () use ($request) {

            $room = Room::create([
                "property_id" => $request->property_id,
                "room_number" => $request->room_number,
                "room_type" => $request->room_type,
                "price_per_night" => $request->price_per_night,
                "num_beds" => $request->num_beds,
                "room_bed_type" => $request->room_bed_type,
                "size_in_sq_m" => 1,
                "capacity" => $request->capacity,
                "current_roomates" => $request->current_roomates,
                "room_amenities" => $request->room_amenities,
                "status" => $request->status,
                "deposit" => $request->deposit,
                'Listing date' => $request->available_from,
                "minimum_stay" => $request->minimum_stay ?? 1,
            ]);

            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainName = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
                $mainImage->storeAs('rooms/main', $mainName, 'public');
                $room->main_image = 'rooms/main/' . $mainName;
            }
            RoomImage::create([
                'room_id' => $room->id,
                'image_path' => 'rooms/main/' . $mainName,
                'is_main' => 1
            ]);
            $additionalImages = [];
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $img) {
                    $name = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                    $img->storeAs('rooms/additional', $name, 'public');
                    $additionalImages[] = 'rooms/additional/' . $name;
                }

                foreach ($additionalImages as $imgPath) {
                    RoomImage::create([
                        'room_id' => $room->id,
                        'image_path' => $imgPath,
                        'is_main' => 0
                    ]);
                }

                $room->additional_images = $additionalImages;
            }

            return $room;
        });
    }

    public function getRoomById($room_id)
    {
        return $room = Room::with('property:id,title')->findOrFail($room_id);
    }


    public function updateRoom($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $room = Room::with('roomImages:id,image_path,is_main')->findOrFail($id);
            $room->update([
                "property_id" => $request->property_id,
                "room_number" => $request->room_number,
                "room_type" => $request->room_type,
                "price_per_night" => $request->price_per_night,
                "num_beds" => $request->num_beds,
                "room_bed_type" => $request->room_bed_type,
                "size_in_sq_m" => $request->size_in_sq_m,
                "capacity" => $request->capacity,
                "current_roomates" => $request->current_roomates,
                "room_amenities" => $request->room_amenities,
                "status" => $request->status,
                "deposit" => $request->deposit,
                'Listing date' => $request->available_from,
                "minimum_stay" => $request->minimum_stay ?? 1,
            ]);

            if ($request->hasFile('main_image')) {
                $oldMain = $room->roomImages->where('is_main', 1)->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->image_path);
                    $oldMain->delete();
                }

                $mainImage = $request->file('main_image');
                $mainName = time() . '_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
                $mainImage->storeAs('rooms/main', $mainName, 'public');

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => 'rooms/main/' . $mainName,
                    'is_main' => 1
                ]);
            }

            if ($request->hasFile('additional_images')) {
                $oldImages = $room->roomImages->where('is_main', 0);
                foreach ($oldImages as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
                foreach ($request->file('additional_images') as $img) {
                    $name = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                    $img->storeAs('rooms/additional', $name, 'public');

                    RoomImage::create([
                        'room_id' => $room->id,
                        'image_path' => 'rooms/additional/' . $name,
                        'is_main' => 0
                    ]);
                }
            }

            return $room;
        });
    }

    public function destroyRoom($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return true;
    }
}
