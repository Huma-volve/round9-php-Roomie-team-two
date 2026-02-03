<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->decimal('loan_amount', 15, 2);    
            $table->decimal('interest_rate', 5, 2);   
            $table->integer('loan_years');            
            $table->date('start_date')->nullable();
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_loans');
    }
};
