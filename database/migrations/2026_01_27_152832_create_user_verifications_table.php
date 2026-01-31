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
       Schema::create('user_verifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->boolean('email_verified')->default(false);
    $table->boolean('phone_verified')->default(false);
    $table->boolean('id_verified')->default(false);
    $table->string('id_document_path')->nullable(); 
    $table->enum('id_type', ['national_id', 'passport', 'driving_license'])->nullable(); // بدون after()
    $table->text('rejection_reason')->nullable();
    $table->timestamps();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
