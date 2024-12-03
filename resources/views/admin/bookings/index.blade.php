<!DOCTYPE html>
<html>
<head>
    <title>Admin - Booking</title>
</head>
<body>
    <h1>Daftar Booking</h1>

    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                    {{ __('Register Account') }}
            </a>

    <ul>
        @foreach ($bookings as $booking)
            <li>
                Ruang: {{ $booking->room->name }} | Tanggal: {{ $booking->booking_date }}
                | Status: {{ $booking->status }}
                <form method="POST" action="/admin/bookings/{{ $booking->id }}/approve">
                    @csrf
                    <button type="submit">Terima</button>
                </form>
                <form method="POST" action="/admin/bookings/{{ $booking->id }}/reject">
                    @csrf
                    <button type="submit">Tolak</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
