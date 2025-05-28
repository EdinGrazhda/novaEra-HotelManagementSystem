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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->string('room_floor');
            $table->enum('room_type', ['single', 'double', 'suite']);
            $table->enum('room_status', ['available', 'occupied', 'maintenance']);
            $table->string('room_description')->nullable();
            $table->unsignedBigInteger('room_category_id');
            $table->foreign('room_category_id')->references('id')->on('roomcategory')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
