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
        Schema::table('guests', function (Blueprint $table) {
            // Add email column (nullable)
            $table->string('email')->nullable()->after('name');
            
            // Add duration_seconds column to track service duration
            $table->integer('duration_seconds')->default(0)->after('status');
            
            // Add completed_at to track when status becomes 'selesai'
            $table->timestamp('completed_at')->nullable()->after('duration_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['email', 'duration_seconds', 'completed_at']);
        });
    }
};
