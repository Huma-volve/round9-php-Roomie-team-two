<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartmentProperties = Property::where('rent_type', 'apartment')->get();
        $roomProperties = Property::where('rent_type', 'room')->get();

        foreach ($apartmentProperties as $property) {
            Room::create([
                'property_id' => $property->id,
                'room_number' => 'A' . rand(100, 999),
                'room_type' => 'private',
                'price_per_night' => rand(6000, 9000),
                'num_beds' => rand(1, 2),
                'room_bed_type' => 'queen',
                'size_in_sq_m' => rand(20, 50),
                'capacity' => rand(2, 4),
                'current_roomates' => 0,
                'room_amenities' => json_encode(['WiFi', 'AC']),
                'status' => 'available',
            ]);
        }

        foreach ($roomProperties as $property) {
            Room::create([
                'property_id' => $property->id,
                'room_number' => 'R' . rand(100, 999),
                'room_type' => 'shared',
                'price_per_night' => rand(1500, 4000),
                'num_beds' => 1,
                'room_bed_type' => 'single',
                'size_in_sq_m' => rand(10, 25),
                'capacity' => 1,
                'current_roomates' => 0,
                'room_amenities' => json_encode(['WiFi']),
                'status' => 'available',
            ]);
        }
    }
}
