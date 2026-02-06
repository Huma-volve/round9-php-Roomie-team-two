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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            
            // Admin who will receive this notification
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            
            // Notification type
            $table->enum('type', [
                'new_booking',
                'booking_cancellation',
                'upcoming_booking',
                'new_review',
                'new_chat_message',
                'payment_received',
                'payment_failed',
                'new_contact_message'
            ]);
            
            // Title and message
            $table->string('title');
            $table->text('message');
            
            // Related entity (polymorphic relationship)
            $table->morphs('notifiable'); // creates notifiable_id and notifiable_type
            
            // Additional data as JSON
            $table->json('data')->nullable();
            
            // Status tracking
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Priority level
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Action URL (optional)
            $table->string('action_url')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['admin_id', 'is_read']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};