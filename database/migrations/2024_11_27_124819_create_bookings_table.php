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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id'); // Kolom room_id yang di-relasikan ke tabel rooms
            $table->string('booked_by'); // Nama pemesan
            $table->dateTime('start_time'); // Waktu mulai pemesanan
            $table->dateTime('end_time'); // Waktu selesai pemesanan
            $table->string('status')->default('pending'); // Status pemesanan
            $table->timestamps();

            // Relasi ke tabel rooms
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
