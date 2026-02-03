<?php

namespace Database\Seeders;

use App\Models\Conversation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create unique conversations for each tenant user (IDs 2-7)
        for ($tenantId = 2; $tenantId <= 7; $tenantId++) {
            Conversation::factory()->create([
                'tenant_id' => $tenantId,
                'admin_id' => 1,
            ]);
        }
    }
}
