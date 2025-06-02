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
        Schema::table('room_menu_orders', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->constrained('rooms');
            $table->foreignId('menu_id')->nullable()->constrained('menu');
            $table->integer('quantity')->default(1);
            $table->enum('status', ['received', 'in_process', 'delivered'])->default('received');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_menu_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('room_id');
            $table->dropConstrainedForeignId('menu_id');
            $table->dropColumn(['quantity', 'status', 'notes']);
        });
    }
};
