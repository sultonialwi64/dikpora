<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'booked_by', 'booking_date', 'user_email', 'jam_awal', 'jam_akhir', 'status'];

    // Relasi ke model Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
