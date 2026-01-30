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
        // Run individual seeders in correct order
        $this->call([
            UserSeeder::class,
            PropertySeeder::class,
            PropertyImageSeeder::class,
            RoomSeeder::class,
            SearchHistorySeeder::class,
            ConversationSeeder::class,
            MessageSeeder::class,
            ReviewSeeder::class,
        ]);

        // Conversations and messages are now handled by ConversationSeeder and MessageSeeder

    }
}
