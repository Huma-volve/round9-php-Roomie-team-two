<?php

namespace Database\Seeders;

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
        Room::create([
            'property_id' => 2,
            'room_number' => '101',
            'room_type' => 'private',
            'price_per_month' => 3000,
            'num_beds' => 1,
            'room_bed_type' => 'queen',
            'size_in_sq_m' => 20,
            'capacity' => 1,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC']),
            'status' => 'available',
        ]);

        Room::create([
            'property_id' => 2,
            'room_number' => '102',
            'room_type' => 'shared',
            'price_per_month' => 2500,
            'num_beds' => 2,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 25,
            'capacity' => 2,
            'current_roomates' => 1,
            'room_amenities' => json_encode(['WiFi']),
            'status' => 'available',
        ]);
    }
}
