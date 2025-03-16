<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Rota principal da aplicação
Route::get('/', [EventController::class, 'index'])->name('events.index');

// Rotas de eventos que requerem autenticação
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
    Route::post('/events/join/{id}/join', [EventController::class, 'joinEvent'])->name('events.join');
    Route::post('/events/{id}/leave', [EventController::class, 'leaveEvent'])->name('events.leave');
    Route::get('/dashboard', [EventController::class, 'dashboard']);
});

// Rotas públicas
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::delete('/events/{id}', [EventController::class, 'destroy']);