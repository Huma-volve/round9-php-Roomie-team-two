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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('room_number');

            $table->enum('room_type', ['private', 'shared']);
            $table->decimal('price_per_month', 10, 2);

            // Room Details
            $table->integer('num_beds')->default(1);
            $table->enum('room_bed_type', ['king', 'queen', 'single', 'double', 'triple', 'quad'])->nullable();
            $table->float('size_in_sq_m');
            $table->integer('capacity')->nullable();
            $table->integer('current_roomates')->nullable();

            $table->json('room_amenities')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
