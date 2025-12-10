<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists
        if (Schema::hasTable('notif_vessel_request')) {
            // First, check if 'id' column exists
            if (!Schema::hasColumn('notif_vessel_request', 'id')) {
                // Add the id column as auto-increment
                DB::statement('ALTER TABLE notif_vessel_request ADD COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST');
            } else {
                // Modify existing id column to be auto-increment
                DB::statement('ALTER TABLE notif_vessel_request MODIFY COLUMN id BIGINT UNSIGNED AUTO_INCREMENT');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to remove the id column in rollback
        // as it might break existing data
    }
};
