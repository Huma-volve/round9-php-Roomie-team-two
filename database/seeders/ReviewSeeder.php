<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'user_id' => 1,
            'property_id' => 1,
            'rating' => 5,
            'comment' => 'Amazing apartment, very clean and cozy!',
        ]);

        Review::create([
            'user_id' => 1,
            'property_id' => 2,
            'rating' => 4,
            'comment' => 'Nice room near metro, very convenient.',
        ]);
    }
}
