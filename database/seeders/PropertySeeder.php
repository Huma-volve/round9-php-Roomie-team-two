<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [];


        for ($i = 1; $i <= 5; $i++) {
            $properties[] = Property::create([
                'admin_id' => 1,
                'title' => "Apartment Property $i",
                'description' => "Sample apartment description $i",
                'rent_type' => 'apartment',
                'price_per_night' => rand(5000, 9000),
                'num_rooms' => rand(2, 4),
                'num_bathrooms' => rand(1, 3),
                'max_guests' => rand(2, 6),
                'gender_preference' => 'both',
                'furnishing' => 'furnished',
                'stay_minimum_in_days' => 30,
                'deposit' => rand(3000, 10000),
                'unit_amenities' => json_encode(['WiFi', 'AC']),
                'lifestyle' => json_encode(['No Smoking']),
                'latitude' => 30.0 + rand(1, 100) / 1000,
                'longitude' => 31.0 + rand(1, 100) / 1000,
                'status' => 'available',
                'available_from' => now(),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $properties[] = Property::create([
                'admin_id' => 1,
                'title' => "Room Property $i",
                'description' => "Sample room description $i",
                'rent_type' => 'room',
                'price_per_night' => rand(1500, 4000),
                'num_rooms' => 1,
                'num_bathrooms' => rand(1, 2),
                'max_guests' => rand(1, 4),
                'gender_preference' => 'both',
                'furnishing' => 'semi-furnished',
                'stay_minimum_in_days' => 30,
                'deposit' => rand(1000, 4000),
                'unit_amenities' => json_encode(['WiFi']),
                'lifestyle' => json_encode(['Shared Living']),
                'latitude' => 30.0 + rand(1, 100) / 1000,
                'longitude' => 31.0 + rand(1, 100) / 1000,
                'status' => 'available',
                'available_from' => now(),
            ]);
        }
    }
}
