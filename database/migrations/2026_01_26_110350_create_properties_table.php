<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');

            // Rental type logic
            $table->enum('rent_type', ['room', 'apartment']);
            $table->decimal('price_per_night', 10, 2)->nullable();

            // Apartment Details
            $table->integer('num_rooms')->default(0);
            $table->integer('num_bathrooms')->default(0);
            $table->integer('max_guests')->default(0);

            // Preferences
            $table->enum('gender_preference', ['male', 'female', 'both'])->nullable();
            $table->enum('furnishing', ['furnished', 'unfurnished', 'semi-furnished'])->nullable();
            $table->integer('stay_minimum_in_days')->nullable();
            $table->string('deposit')->nullable();

            // Unit Amenities and Lifestyle as json
            $table->json('unit_amenities')->nullable();
            $table->json('lifestyle')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->date('available_from')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
