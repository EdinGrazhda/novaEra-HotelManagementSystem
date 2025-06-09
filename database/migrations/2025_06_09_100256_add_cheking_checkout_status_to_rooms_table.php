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
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('checkin_status')->default('not_checked_in')->after('room_description');
            $table->string('checkout_status')->default('not_checked_out')->after('checkin_status');
            $table->timestamp('checkin_time')->nullable()->after('checkout_status');
            $table->timestamp('checkout_time')->nullable()->after('checkin_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('checkin_status');
            $table->dropColumn('checkout_status');
            $table->dropColumn('checkin_time');
            $table->dropColumn('checkout_time');
        });
    }
};
