<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notif_vessel_request', function (Blueprint $table) {
            $table->boolean('deleted_by_user')->default(false)->after('status');
            $table->timestamp('user_deleted_at')->nullable()->after('deleted_by_user');
        });
    }

    public function down()
    {
        Schema::table('notif_vessel_request', function (Blueprint $table) {
            $table->dropColumn(['deleted_by_user', 'user_deleted_at']);
        });
    }
};
