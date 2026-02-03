<?php

namespace Database\Seeders;

use App\Models\PropertyImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyImage::create([
            'property_id' => 1,
            'image_path' => 'images/property1_main.jpg',
            'is_main' => true,
        ]);

        PropertyImage::create([
            'property_id' => 2,
            'image_path' => 'images/property2_main.jpg',
            'is_main' => true,
        ]);
    }
}
