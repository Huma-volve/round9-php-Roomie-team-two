<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Database\Seeder;
use Database\Seeders\MessageSeeder;
use Database\Seeders\ConversationSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $tenants = User::where('is_admin', false)->get();
        $admin = User::where('is_admin', true)->first();

        foreach ($tenants as $tenant) {
            $conversation = Conversation::factory()->create([
                'tenant_id' => $tenant->id,
                'admin_id'  => $admin->id,
                'last_message' => 'Hello Admin!',
                'last_message_at' => now(),
            ]);

            Message::factory()->count(5)->create([
                'conversation_id' => $conversation->id,
                'sender_id' => $tenant->id, // رسالة من المستأجر
            ]);

            Message::factory()->count(2)->create([
                'conversation_id' => $conversation->id,
                'sender_id' => $admin->id, 
            ]);
        }

    }
}
