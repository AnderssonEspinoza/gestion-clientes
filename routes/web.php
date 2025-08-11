<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;  // <-- Asegúrate de importarlo
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rutas perfil (ya existentes)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas clientes (nuevas)
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/mis', [ClienteController::class, 'mis'])->name('clientes.mis');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::post('/clientes/{id}/assign', [ClienteController::class, 'assign'])->name('clientes.assign');
});

Route::post('/clientes/{cliente}/assign', [App\Http\Controllers\ClienteController::class, 'assign'])
    ->name('clientes.assign')
    ->middleware('auth');


Route::get('/mis-clientes', [ClienteController::class, 'misClientes'])
    ->name('clientes.mis')
    ->middleware('auth');



require __DIR__.'/auth.php';
