<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Rutas a las diferentes páginas de la web.
Route::get('/home', function () {
    return Inertia::render('Home');
})->name('home');
Route::get('/adopta', [AnimalController::class, 'index'])->name('adopta');
Route::get('/perros-recomendados', [\App\Http\Controllers\RecommendationController::class, 'index'])->name('perros-recomendados');
Route::get('/donaciones', [\App\Http\Controllers\DonacionController::class, 'index'])->name('donaciones');
Route::post('/donaciones', [\App\Http\Controllers\DonacionController::class, 'store'])->name('donaciones.store');

Route::get('/contacto', function () {
    return Inertia::render('Contacto');
});
require __DIR__ . '/auth.php';
