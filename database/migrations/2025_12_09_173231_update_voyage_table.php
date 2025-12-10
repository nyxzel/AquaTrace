<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This ensures the voyage table structure matches our requirements
     */
    public function up(): void
    {
        // Check if table exists and update it
        if (Schema::hasTable('voyage')) {
            Schema::table('voyage', function (Blueprint $table) {
                // Only add columns if they don't exist
                if (!Schema::hasColumn('voyage', 'created_at')) {
                    $table->timestamps();
                }

                // Ensure status column has correct enum values
                if (Schema::hasColumn('voyage', 'status')) {
                    DB::statement("ALTER TABLE voyage MODIFY status ENUM('active', 'docked', 'in_transmit') DEFAULT 'docked'");
                }
            });
        } else {
            // Create table if it doesn't exist
            Schema::create('voyage', function (Blueprint $table) {
                $table->id('voyage_id');
                $table->unsignedBigInteger('vessel_id');
                $table->unsignedBigInteger('departure_port')->nullable();
                $table->unsignedBigInteger('arrival_port')->nullable();
                $table->date('departure_date')->nullable();
                $table->date('arrival_date')->nullable();
                $table->enum('status', ['active', 'docked', 'in_transmit'])->default('docked');
                $table->timestamps();

                // Foreign keys
                $table->foreign('vessel_id')->references('vessel_id')->on('vessels')->onDelete('cascade');
                $table->foreign('departure_port')->references('port_id')->on('ports')->onDelete('set null');
                $table->foreign('arrival_port')->references('port_id')->on('ports')->onDelete('set null');

                // Indexes
                $table->index('vessel_id');
                $table->index('status');
                $table->index(['departure_port', 'arrival_port']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in down() to prevent data loss
        // Just remove the columns we added
        if (Schema::hasTable('voyage')) {
            Schema::table('voyage', function (Blueprint $table) {
                if (Schema::hasColumn('voyage', 'created_at')) {
                    $table->dropColumn(['created_at', 'updated_at']);
                }
            });
        }
    }
};
