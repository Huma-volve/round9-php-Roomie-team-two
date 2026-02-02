<?php

namespace Database\Seeders;

use App\Models\SearchHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SearchHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test user search histories (assuming user ID 1 exists)
        $searches = [
            [
                'user_id' => 1,
                'property_type' => 'apartment',
                'bhk' => 2,
                'min_budget' => 5000,
                'max_budget' => 10000,
                'locality' => 'Downtown',
                'latitude' => 30.0444,
                'longitude' => 31.2357,
                'radius_km' => 10,
                'results_count' => 8,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => 1,
                'property_type' => 'room',
                'min_budget' => 2000,
                'max_budget' => 4000,
                'locality' => 'Heliopolis',
                'latitude' => 30.0925,
                'longitude' => 31.3217,
                'radius_km' => 5,
                'results_count' => 12,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => 1,
                'bhk' => 1,
                'min_budget' => 1500,
                'max_budget' => 3000,
                'locality' => 'Zamalek',
                'results_count' => 6,
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'user_id' => 1,
                'property_type' => 'apartment',
                'bhk' => 3,
                'min_budget' => 6000,
                'max_budget' => 8000,
                'locality' => 'Maadi',
                'latitude' => 29.9667,
                'longitude' => 31.2833,
                'radius_km' => 8,
                'results_count' => 4,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => 1,
                'property_type' => 'room',
                'min_budget' => 1000,
                'max_budget' => 2500,
                'locality' => 'Alexandria',
                'latitude' => 31.2000,
                'longitude' => 29.9167,
                'radius_km' => 15,
                'results_count' => 9,
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'user_id' => 1,
                'locality' => 'Student Housing',
                'min_budget' => 1500,
                'max_budget' => 3000,
                'results_count' => 7,
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'user_id' => 1,
                'property_type' => 'apartment',
                'bhk' => 2,
                'min_budget' => 4000,
                'max_budget' => 7000,
                'latitude' => 30.0667,
                'longitude' => 31.2167,
                'radius_km' => 12,
                'results_count' => 11,
                'created_at' => Carbon::now()->subMinutes(30),
            ],
        ];

        foreach ($searches as $search) {
            SearchHistory::create($search);
        }
    }
}
