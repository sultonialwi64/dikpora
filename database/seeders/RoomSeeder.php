<?php

namespace Database\Seeders;
use App\Models\Booking; // Pastikan model Booking diimpor

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Room::create(['name' => 'Ruang A', 'capacity' => 10]);
        Room::create(['name' => 'Ruang B', 'capacity' => 20]);
        Room::create(['name' => 'Ruang C', 'capacity' => 15]);
    }
}
