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
        // Rooms for Downtown Cairo Properties (Property ID: 1 - Luxury Downtown Apartment)
        Room::create([
            'property_id' => 1,
            'room_number' => 'A101',
            'room_type' => 'private',
            'price_per_night' => 8500,
            'num_beds' => 1,
            'room_bed_type' => 'king',
            'size_in_sq_m' => 35,
            'capacity' => 2,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC', 'TV', 'Mini Bar']),
            'status' => 'available',
        ]);

        Room::create([
            'property_id' => 1,
            'room_number' => 'A102',
            'room_type' => 'private',
            'price_per_night' => 9000,
            'num_beds' => 2,
            'room_bed_type' => 'queen',
            'size_in_sq_m' => 40,
            'capacity' => 4,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC', 'Balcony', 'City View']),
            'status' => 'available',
        ]);

        // Rooms for Cozy Downtown Studio (Property ID: 2)
        Room::create([
            'property_id' => 2,
            'room_number' => '101',
            'room_type' => 'private',
            'price_per_night' => 3000,
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
            'price_per_night' => 2500,
            'num_beds' => 2,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 25,
            'capacity' => 2,
            'current_roomates' => 1,
            'room_amenities' => json_encode(['WiFi']),
            'status' => 'available',
        ]);

        // Rooms for Modern Heliopolis Villa (Property ID: 3)
        Room::create([
            'property_id' => 3,
            'room_number' => 'H201',
            'room_type' => 'private',
            'price_per_night' => 6500,
            'num_beds' => 2,
            'room_bed_type' => 'king',
            'size_in_sq_m' => 45,
            'capacity' => 4,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC', 'Garden View', 'Walk-in Closet']),
            'status' => 'available',
        ]);

        Room::create([
            'property_id' => 3,
            'room_number' => 'H202',
            'room_type' => 'private',
            'price_per_night' => 6000,
            'num_beds' => 1,
            'room_bed_type' => 'queen',
            'size_in_sq_m' => 30,
            'capacity' => 2,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC', 'Balcony']),
            'status' => 'available',
        ]);

        // Rooms for Shared House Heliopolis (Property ID: 6)
        Room::create([
            'property_id' => 6,
            'room_number' => 'SH101',
            'room_type' => 'shared',
            'price_per_night' => 2800,
            'num_beds' => 1,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 15,
            'capacity' => 1,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC', 'Shared Kitchen']),
            'status' => 'available',
        ]);

        Room::create([
            'property_id' => 6,
            'room_number' => 'SH102',
            'room_type' => 'shared',
            'price_per_night' => 2600,
            'num_beds' => 1,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 18,
            'capacity' => 1,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'Shared Laundry']),
            'status' => 'available',
        ]);

        // Rooms for Student Housing Downtown (Property ID: 7)
        Room::create([
            'property_id' => 7,
            'room_number' => 'ST201',
            'room_type' => 'shared',
            'price_per_night' => 2000,
            'num_beds' => 1,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 12,
            'capacity' => 1,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'Study Desk']),
            'status' => 'available',
        ]);

        Room::create([
            'property_id' => 7,
            'room_number' => 'ST202',
            'room_type' => 'shared',
            'price_per_night' => 1900,
            'num_beds' => 1,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 14,
            'capacity' => 1,
            'current_roomates' => 1,
            'room_amenities' => json_encode(['WiFi', 'Shared Study Area']),
            'status' => 'available',
        ]);

        // Rooms for Alexandria Properties (Property ID: 9 - Seaside Alexandria Apartment)
        Room::create([
            'property_id' => 9,
            'room_number' => 'AL101',
            'room_type' => 'private',
            'price_per_night' => 7000,
            'num_beds' => 1,
            'room_bed_type' => 'queen',
            'size_in_sq_m' => 28,
            'capacity' => 2,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'AC', 'Sea View', 'Balcony']),
            'status' => 'available',
        ]);

        // Rooms for Budget Alexandria Room (Property ID: 10)
        Room::create([
            'property_id' => 10,
            'room_number' => 'AB101',
            'room_type' => 'shared',
            'price_per_night' => 1600,
            'num_beds' => 1,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 10,
            'capacity' => 1,
            'current_roomates' => 0,
            'room_amenities' => json_encode(['WiFi', 'Shared Kitchen']),
            'status' => 'available',
        ]);

        Room::create([
            'property_id' => 10,
            'room_number' => 'AB102',
            'room_type' => 'shared',
            'price_per_night' => 1550,
            'num_beds' => 1,
            'room_bed_type' => 'single',
            'size_in_sq_m' => 12,
            'capacity' => 1,
            'current_roomates' => 1,
            'room_amenities' => json_encode(['WiFi', 'Shared Bathroom']),
            'status' => 'available',
        ]);
    }
}
