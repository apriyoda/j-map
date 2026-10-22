<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaceController;
use Illuminate\Support\Facades\Route;

// Public route: peta publik utama (welcome / Leaflet map)
Route::get('/', [PlaceController::class, 'welcome'])->name('home');

Route::get('/dashboard', function () {
    $stats = [
        'total_places' => \App\Models\Place::count(),
        'pending_places' => \App\Models\Place::where('status', 'pending')->count(),
        'total_users' => \App\Models\User::count(),
    ];
    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengajuan tempat (dapat diakses oleh Member dan Admin)
    Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create');
    Route::post('/places', [PlaceController::class, 'store'])->name('places.store');
    Route::get('/places/suggest', [PlaceController::class, 'createSuggestion'])->name('places.suggest');
    Route::post('/places/suggest', [PlaceController::class, 'storeSuggestion'])->name('places.suggest.store');

    // Khusus Admin: kelola daftar tempat (CRUD) dan verifikasi pengajuan
    Route::middleware('admin')->group(function () {
        Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
        Route::get('/places/{place}/edit', [PlaceController::class, 'edit'])->name('places.edit');
        Route::put('/places/{place}', [PlaceController::class, 'update'])->name('places.update');
        Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy');

        // Admin pending approval routes
        Route::get('/admin/places/pending', [PlaceController::class, 'adminIndex'])->name('admin.places.pending');
        Route::post('/places/{place}/approve', [PlaceController::class, 'approve'])->name('places.approve');
        Route::post('/places/{place}/reject', [PlaceController::class, 'reject'])->name('places.reject');
    });
});

require __DIR__.'/auth.php';