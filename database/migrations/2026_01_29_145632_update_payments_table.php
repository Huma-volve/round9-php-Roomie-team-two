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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('reservation_code')->unique()->after('booking_id');
            $table->enum('payment_type', ['full', 'partial', 'installment'])->default('full')->after('payment_method');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('amount');
            $table->string('stripe_payment_intent_id')->nullable()->after('amount_paid');
            $table->json('stripe_response')->nullable()->after('stripe_payment_intent_id');
            $table->timestamp('payment_due_date')->nullable()->after('stripe_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('stripe_payment_intent_id');
            $table->dropColumn('amount_paid');
            $table->dropColumn('stripe_response');
            $table->dropColumn('payment_due_date');
        });
    }
};
