<?php

namespace Database\Seeders;
use App\Models\Booking; // Pastikan model Booking diimpor

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoomSeeder::class);
    }
}
