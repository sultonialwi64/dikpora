<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Home</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-blue-600 text-white p-6">
            <h1 class="text-2xl font-bold mb-8">Menu</h1>
            <ul>
                <li><a href="#" class="block py-2 hover:bg-blue-700 rounded">Dashboard</a></li>
                <li><a href="#" class="block py-2 hover:bg-blue-700 rounded">Peminjaman Ruang</a></li>
                <li><a href="#" class="block py-2 hover:bg-blue-700 rounded">Pengaturan</a></li>
                <li><a href="#" class="block py-2 hover:bg-blue-700 rounded">Keluar</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- App Bar (Header) -->
            <div class="bg-blue-600 text-white p-4 flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Dashboard Peminjaman Ruang</h1>
                <div class="text-lg">{{ auth()->user()->name }}</div>
            </div>

            <!-- Container -->
            <div class="flex-1 p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Peminjaman Ruang Anda</h2>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Status Peminjaman</h3>

                    <!-- Loop untuk menampilkan peminjaman berdasarkan user yang login -->
                    @foreach ($bookings as $booking)
                        <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-lg font-semibold text-gray-600">{{ $booking->room->name }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <!-- Menampilkan tanggal dalam format "27 Juni 2004" -->
                                    <span class="text-sm text-gray-500">{{ $booking->formatted_booking_date }}</span>
                                    <!-- Menampilkan jam -->
                                    <span class="text-sm text-gray-500">{{ $booking->jam_awal }} - {{ $booking->jam_akhir }}</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                @if ($booking->status == 'accepted')
                                    <span class="text-green-500">Booking Disetujui</span>
                                @elseif ($booking->status == 'pending')
                                    <span class="text-yellow-500">Booking Menunggu Konfirmasi</span>
                                @else
                                    <span class="text-red-500">Booking Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</body>
</html>
