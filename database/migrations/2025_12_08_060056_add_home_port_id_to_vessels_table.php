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
        // Check if home_port_id column doesn't exist before adding
        if (!Schema::hasColumn('vessels', 'home_port_id')) {
            Schema::table('vessels', function (Blueprint $table) {
                $table->unsignedBigInteger('home_port_id')->nullable()->after('admin_id');

                // Add foreign key constraint
                $table->foreign('home_port_id')
                    ->references('port_id')
                    ->on('ports')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vessels', 'home_port_id')) {
            Schema::table('vessels', function (Blueprint $table) {
                // Drop foreign key first
                $table->dropForeign(['home_port_id']);
                // Then drop column
                $table->dropColumn('home_port_id');
            });
        }
    }
};
