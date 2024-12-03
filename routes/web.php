<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Redirect after login (dashboard for authenticated users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes for users and admins
Route::middleware(['auth', 'isAdmin'])->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-specific booking routes
    Route::get('/admin/bookings', [BookingController::class, 'index'])->name('admin.bookings.index'); // View all bookings
    Route::post('/admin/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve'); // Approve booking
    Route::post('/admin/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject'); // Reject booking
    Route::post('/addUsers', [RegisteredUserController::class,'saveUser'])->name('addUsers');
});

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // User-specific booking routes
    Route::get('/book-rooms', [RoomController::class, 'index'])->name('rooms.index'); // View rooms
    Route::post('/check-availability', [RoomController::class, 'checkAvailability']);
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store'); // Submit booking request
    Route::get('/home', [BookingController::class, 'home'])->name('home');
});


require __DIR__.'/auth.php';
