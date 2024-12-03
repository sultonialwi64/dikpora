<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ruangan</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Daftar Ruangan</h1>

        <!-- Tampilkan pesan sukses -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tampilkan pesan error -->
        @if ($errors->has('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ $errors->first('error') }}
            </div>
        @endif

        <ul class="space-y-6">
            @foreach ($rooms as $room)
                <li class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-lg text-gray-700">{{ $room->name }}</span>
                        <span class="text-sm text-gray-500">Kapasitas: {{ $room->capacity }} orang</span>
                    </div>

                    <form method="POST" action="/book" class="mt-4">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <!-- Nama "Booked By" otomatis terisi dengan nama user yang sedang login -->
                        <div class="mb-4">
                            <label for="booked_by" class="block text-sm font-medium text-gray-700">Nama Anda</label>
                            <input type="text" name="booked_by" id="booked_by" value="{{ auth()->user()->name }}" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100 cursor-not-allowed" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="user_email" class="block text-sm font-medium text-gray-700">Email Anda</label>
                            <input type="email" name="user_email" id="user_email" value="{{ auth()->user()->email }}" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100 cursor-not-allowed" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="booking_date_{{ $room->id }}" class="block text-sm font-medium text-gray-700">Tanggal Booking</label>
                            <input type="date" name="booking_date" id="booking_date_{{ $room->id }}" required
                                onchange="checkAvailableTimes({{ $room->id }})"
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="mb-4">
                            <label for="jam_awal_{{ $room->id }}" class="block text-sm font-medium text-gray-700">Pilih Jam Awal</label>
                            <input type="time" name="jam_awal" id="jam_awal_{{ $room->id }}" required
                                onchange="checkAvailabilityForTimes({{ $room->id }})"
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="mb-4">
                            <label for="jam_akhir_{{ $room->id }}" class="block text-sm font-medium text-gray-700">Pilih Jam Akhir</label>
                            <input type="time" name="jam_akhir" id="jam_akhir_{{ $room->id }}" required
                                onchange="checkAvailabilityForTimes({{ $room->id }})"
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div id="availability-message_{{ $room->id }}" class="text-red-600 font-medium mb-4"></div>

                        <button type="submit" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Ajukan Booking
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        // Fungsi untuk mengecek ketersediaan waktu langsung saat pengguna memilih jam
        function checkAvailabilityForTimes(roomId) {
            const bookingDate = document.getElementById(`booking_date_${roomId}`).value;
            const jamAwal = document.getElementById(`jam_awal_${roomId}`).value;
            const jamAkhir = document.getElementById(`jam_akhir_${roomId}`).value;

            // Pastikan tanggal dan jam awal serta jam akhir sudah dipilih
            if (!bookingDate || !jamAwal || !jamAkhir) return;

            // Kirim permintaan untuk mengecek ketersediaan waktu
            axios.post('/check-availability', {
                room_id: roomId,
                booking_date: bookingDate,
                jam_awal: jamAwal,
                jam_akhir: jamAkhir
            }).then(response => {
                const availabilityMessage = document.getElementById(`availability-message_${roomId}`);

                if (response.data.available) {
                    availabilityMessage.textContent = 'Waktu tersedia!';
                    availabilityMessage.style.color = 'green';
                } else {
                    availabilityMessage.textContent = 'Waktu sudah dibooking, silakan pilih waktu lain.';
                    availabilityMessage.style.color = 'red';
                }
            }).catch(error => {
                console.error(error);
            });
        }
    </script>
</body>
</html>
