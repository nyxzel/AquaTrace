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
        Schema::create('voyage_requests', function (Blueprint $table) {
            $table->id('request_id');

            // Reference to vessel and user
            $table->unsignedBigInteger('vessel_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('owner_id');

            // Current location (where vessel is now)
            $table->decimal('current_latitude', 10, 6);
            $table->decimal('current_longitude', 10, 6);
            $table->string('current_location_name')->nullable(); // e.g., "Sasa Wharf"

            // Destination
            $table->decimal('destination_latitude', 10, 6)->nullable();
            $table->decimal('destination_longitude', 10, 6)->nullable();
            $table->string('destination_name')->nullable(); // e.g., "Panabo Port"

            // Port IDs (if applicable)
            $table->unsignedBigInteger('departure_port_id')->nullable();
            $table->unsignedBigInteger('arrival_port_id')->nullable();

            // Voyage details
            $table->enum('requested_status', ['Active', 'Docked', 'In Transit'])->default('Docked');
            $table->dateTime('departure_datetime')->nullable();
            $table->dateTime('arrival_datetime')->nullable();
            $table->text('notes')->nullable();

            // Approval workflow
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();

            // Timestamps for workflow
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Foreign keys - Fixed syntax
            $table->foreign('vessel_id')
                ->references('vessel_id')
                ->on('vessels')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('owner_id')
                ->references('owner_id')
                ->on('owners')
                ->onDelete('cascade');

            $table->foreign('departure_port_id')
                ->references('port_id')
                ->on('ports')
                ->onDelete('set null');

            $table->foreign('arrival_port_id')
                ->references('port_id')
                ->on('ports')
                ->onDelete('set null');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('rejected_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Indexes
            $table->index('vessel_id');
            $table->index('status');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyage_requests');
    }
};
