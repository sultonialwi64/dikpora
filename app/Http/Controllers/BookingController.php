<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booked_by' => 'required|string|max:255',
            'user_email' => 'required|email',
            'booking_date' => 'required|date',
            'jam_awal' => 'required|date_format:H:i', // Validasi format jam awal
            'jam_akhir' => 'required|date_format:H:i|after:jam_awal', // Validasi jam akhir harus setelah jam awal
        ]);

        // Cek apakah sudah ada booking di tanggal dan waktu yang tumpang tindih
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

        if ($isBooked) {
            return back()->withErrors(['error' => 'Ruangan sudah dibooking pada tanggal dan waktu yang dipilih.'])->withInput();
        }

        // Simpan data booking jika tersedia
        Booking::create([
            'room_id' => $request->room_id,
            'booked_by' => $request->booked_by,
            'user_email' => $request->user_email,
            'booking_date' => $request->booking_date,
            'jam_awal' => $request->jam_awal, // Simpan jam awal
            'jam_akhir' => $request->jam_akhir, // Simpan jam akhir
        ]);

        return redirect('/book-rooms')->with('success', 'Booking berhasil diajukan!');
    }

    public function index()
    {
        // Ambil semua booking
        $bookings = Booking::with('room')->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function home()
    {
        // Mengambil peminjaman ruang berdasarkan user yang sedang login
        $userName = auth()->user()->name;  // Ambil nama user yang sedang login

        // Ambil peminjaman ruang berdasarkan nama user
        $bookings = Booking::where('booked_by', $userName)->get();

        // Format tanggal
        foreach ($bookings as $booking) {
            $booking->formatted_booking_date = Carbon::parse($booking->booking_date)->translatedFormat('d F Y'); // 27 Juni 2004
        }

        // Menampilkan halaman home dengan data peminjaman
        return view('rooms.home', compact('bookings'));
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'accepted']);
        return redirect('/admin/bookings')->with('success', 'Booking approved.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'rejected']);
        return redirect('/admin/bookings')->with('success', 'Booking rejected.');
    }
}
