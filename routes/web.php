<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
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

    // Rutas clientes (existentes)
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::post('/clientes/{id}/assign', [ClienteController::class, 'assign'])->name('clientes.assign');
    
    // Ruta para mis clientes
    Route::get('/mis-clientes', [ClienteController::class, 'misClientes'])->name('mis-clientes');
    
    // NUEVA RUTA: Para actualizar el estado del cliente
    Route::patch('/mis-clientes/{id}/estado', [ClienteController::class, 'updateEstado'])->name('clientes.updateEstado');
    
    // Opcional: Ruta para finalizar cliente
    Route::patch('/mis-clientes/{id}/finalizar', [ClienteController::class, 'finalizar'])->name('clientes.finalizar');
    // Ruta para ver un cliente específico en mis clientes
    Route::get('/mis-clientes/{cliente}', [ClienteController::class, 'showMisClientes'])->name('mis-clientes.show');

});

// Ruta duplicada removida (ya está arriba en el grupo middleware)
// Route::post('/clientes/{cliente}/assign', [App\Http\Controllers\ClienteController::class, 'assign'])

require __DIR__.'/auth.php';