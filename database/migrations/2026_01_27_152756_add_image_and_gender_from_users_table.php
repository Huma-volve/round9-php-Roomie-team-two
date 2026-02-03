<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->renameColumn('title', 'job_title');
            
            
            $table->string('image')->nullable()->after('name');
            
           
            $table->enum('gender', ['male', 'female'])->nullable()->after('job_title');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->integer('max_budget')->nullable();
            $table->dropColumn('gender');
            $table->dropColumn('image'); 
            $table->renameColumn('job_title', 'title');
        });
    }
};