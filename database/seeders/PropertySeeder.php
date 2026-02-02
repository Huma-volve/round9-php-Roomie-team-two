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
        // Downtown Cairo Properties
        Property::create([
            'admin_id' => 1,
            'title' => 'Luxury Downtown Apartment',
            'description' => 'Modern luxury apartment in downtown Cairo with city views.',
            'rent_type' => 'apartment',
            'price_per_night' => 8000,
            'num_rooms' => 3,
            'num_bathrooms' => 2,
            'max_guests' => 6,
            'gender_preference' => 'both',
            'furnishing' => 'furnished',
            'stay_minimum_in_days' => 30,
            'deposit' => '10000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Gym', 'Pool']),
            'lifestyle' => json_encode(['No Smoking', 'No Pets']),
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'status' => 'available',
            'available_from' => now(),
        ]);

        Property::create([
            'admin_id' => 1,
            'title' => 'Cozy Downtown Studio',
            'description' => 'Perfect studio apartment for singles or couples.',
            'rent_type' => 'apartment',
            'price_per_night' => 4000,
            'num_rooms' => 1,
            'num_bathrooms' => 1,
            'max_guests' => 2,
            'gender_preference' => 'both',
            'furnishing' => 'furnished',
            'stay_minimum_in_days' => 15,
            'deposit' => '3000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Kitchen']),
            'lifestyle' => json_encode(['Quiet Hours']),
            'latitude' => 30.0450,
            'longitude' => 31.2360,
            'status' => 'available',
            'available_from' => now(),
        ]);

        // Heliopolis Area
        Property::create([
            'admin_id' => 1,
            'title' => 'Modern Heliopolis Villa',
            'description' => 'Spacious villa in quiet Heliopolis neighborhood.',
            'rent_type' => 'apartment',
            'price_per_night' => 6000,
            'num_rooms' => 4,
            'num_bathrooms' => 3,
            'max_guests' => 8,
            'gender_preference' => 'both',
            'furnishing' => 'semi-furnished',
            'stay_minimum_in_days' => 60,
            'deposit' => '8000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Garden', 'Parking']),
            'lifestyle' => json_encode(['Family Friendly']),
            'latitude' => 30.0925,
            'longitude' => 31.3217,
            'status' => 'available',
            'available_from' => now(),
        ]);

        // Zamalek Area
        Property::create([
            'admin_id' => 1,
            'title' => 'Elegant Zamalek Apartment',
            'description' => 'Beautiful apartment in the prestigious Zamalek district.',
            'rent_type' => 'apartment',
            'price_per_night' => 7000,
            'num_rooms' => 2,
            'num_bathrooms' => 2,
            'max_guests' => 4,
            'gender_preference' => 'both',
            'furnishing' => 'furnished',
            'stay_minimum_in_days' => 30,
            'deposit' => '6000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Balcony', 'Security']),
            'lifestyle' => json_encode(['Upscale', 'No Smoking']),
            'latitude' => 30.0667,
            'longitude' => 31.2167,
            'status' => 'available',
            'available_from' => now(),
        ]);

        // Maadi Area
        Property::create([
            'admin_id' => 1,
            'title' => 'Green Maadi Townhouse',
            'description' => 'Charming townhouse in the green Maadi area.',
            'rent_type' => 'apartment',
            'price_per_night' => 5500,
            'num_rooms' => 3,
            'num_bathrooms' => 2,
            'max_guests' => 6,
            'gender_preference' => 'both',
            'furnishing' => 'unfurnished',
            'stay_minimum_in_days' => 45,
            'deposit' => '5000',
            'unit_amenities' => json_encode(['WiFi', 'Garden', 'Parking']),
            'lifestyle' => json_encode(['Family Friendly', 'Pet Friendly']),
            'latitude' => 29.9667,
            'longitude' => 31.2833,
            'status' => 'available',
            'available_from' => now(),
        ]);

        // Individual Rooms in Shared Properties
        Property::create([
            'admin_id' => 1,
            'title' => 'Shared House Heliopolis',
            'description' => 'Comfortable shared house for young professionals.',
            'rent_type' => 'room',
            'price_per_night' => 2500,
            'num_rooms' => 1,
            'num_bathrooms' => 2,
            'max_guests' => 4,
            'gender_preference' => 'both',
            'furnishing' => 'semi-furnished',
            'stay_minimum_in_days' => 30,
            'deposit' => '2000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Shared Kitchen']),
            'lifestyle' => json_encode(['Young Professionals']),
            'latitude' => 30.0930,
            'longitude' => 31.3220,
            'status' => 'available',
            'available_from' => now(),
        ]);

        Property::create([
            'admin_id' => 1,
            'title' => 'Student Housing Downtown',
            'description' => 'Affordable housing for students near universities.',
            'rent_type' => 'room',
            'price_per_night' => 1800,
            'num_rooms' => 1,
            'num_bathrooms' => 3,
            'max_guests' => 6,
            'gender_preference' => 'both',
            'furnishing' => 'semi-furnished',
            'stay_minimum_in_days' => 90,
            'deposit' => '1500',
            'unit_amenities' => json_encode(['WiFi', 'Study Area', 'Laundry']),
            'lifestyle' => json_encode(['Students Only', 'Quiet Study Hours']),
            'latitude' => 30.0470,
            'longitude' => 31.2380,
            'status' => 'available',
            'available_from' => now(),
        ]);

        Property::create([
            'admin_id' => 1,
            'title' => 'Luxury Shared Villa Maadi',
            'description' => 'High-end shared accommodation in Maadi.',
            'rent_type' => 'room',
            'price_per_night' => 4000,
            'num_rooms' => 1,
            'num_bathrooms' => 4,
            'max_guests' => 8,
            'gender_preference' => 'both',
            'furnishing' => 'furnished',
            'stay_minimum_in_days' => 60,
            'deposit' => '4000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Pool', 'Gym', 'Security']),
            'lifestyle' => json_encode(['Luxury Lifestyle', 'Professionals Only']),
            'latitude' => 29.9680,
            'longitude' => 31.2840,
            'status' => 'available',
            'available_from' => now(),
        ]);

        // Alexandria Properties (for location diversity)
        Property::create([
            'admin_id' => 1,
            'title' => 'Seaside Alexandria Apartment',
            'description' => 'Beautiful apartment with Mediterranean Sea views.',
            'rent_type' => 'apartment',
            'price_per_night' => 6500,
            'num_rooms' => 2,
            'num_bathrooms' => 2,
            'max_guests' => 4,
            'gender_preference' => 'both',
            'furnishing' => 'furnished',
            'stay_minimum_in_days' => 30,
            'deposit' => '7000',
            'unit_amenities' => json_encode(['WiFi', 'AC', 'Sea View', 'Balcony']),
            'lifestyle' => json_encode(['Beach Lifestyle']),
            'latitude' => 31.2000,
            'longitude' => 29.9167,
            'status' => 'available',
            'available_from' => now(),
        ]);

        Property::create([
            'admin_id' => 1,
            'title' => 'Budget Alexandria Room',
            'description' => 'Affordable room for students and young professionals.',
            'rent_type' => 'room',
            'price_per_night' => 1500,
            'num_rooms' => 1,
            'num_bathrooms' => 2,
            'max_guests' => 3,
            'gender_preference' => 'both',
            'furnishing' => 'semi-furnished',
            'stay_minimum_in_days' => 30,
            'deposit' => '1000',
            'unit_amenities' => json_encode(['WiFi', 'Shared Kitchen']),
            'lifestyle' => json_encode(['Budget Conscious']),
            'latitude' => 31.2050,
            'longitude' => 29.9180,
            'status' => 'available',
            'available_from' => now(),
        ]);
    }
}
