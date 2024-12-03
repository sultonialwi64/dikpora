<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date',
            'jam_awal' => 'required|date_format:H:i',
            'jam_akhir' => 'required|date_format:H:i|after:jam_awal',
        ]);

        $isBooked = Booking::where('room_id', $request->room_id)
            ->where('booking_date', $request->booking_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_awal', [$request->jam_awal, $request->jam_akhir])
                    ->orWhereBetween('jam_akhir', [$request->jam_awal, $request->jam_akhir])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('jam_awal', '<=', $request->jam_awal)
                            ->where('jam_akhir', '>=', $request->jam_akhir);
                    });
            })
            ->exists();

        return response()->json(['available' => !$isBooked]);
    }
}
