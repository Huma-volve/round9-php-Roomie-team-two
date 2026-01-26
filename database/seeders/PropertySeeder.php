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
        Property::create([
            'admin_id' => 1,
            'title' => 'Cozy Apartment Downtown',
            'description' => 'A nice cozy apartment in the heart of the city.',
            'rent_type' => 'apartment',
            'price_per_month' => 5000,
            'num_rooms' => 2,
            'num_bathrooms' => 1,
            'max_guests' => 4,
            'gender_preference' => 'both',
            'furnishing' => 'furnished',
            'stay_minimum_in_days' => 30,
            'deposit' => '5000',
            'unit_amenities' => json_encode(['WiFi', 'AC']),
            'lifestyle' => json_encode(['No Smoking']),
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'status' => 'available',
            'available_from' => now(),
        ]);

        Property::create([
            'admin_id' => 1,
            'title' => 'Sunny Room Near Metro',
            'description' => 'Bright and sunny room with private bathroom.',
            'rent_type' => 'room',
            'price_per_month' => 3000,
            'num_rooms' => 1,
            'num_bathrooms' => 1,
            'max_guests' => 1,
            'gender_preference' => 'female',
            'furnishing' => 'semi-furnished',
            'stay_minimum_in_days' => 15,
            'deposit' => '2000',
            'unit_amenities' => json_encode(['WiFi']),
            'lifestyle' => json_encode(['No Pets']),
            'latitude' => 30.0500,
            'longitude' => 31.2400,
            'status' => 'available',
            'available_from' => now(),
        ]);
    }
}
