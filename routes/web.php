<?php


use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index']);
Route::get('/events/create', [EventController::class, 'create'])->middleware('auth');
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events',[EventController::class,'store']);
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/dashboard',[EventController::class,'dashboard'])->middleware('auth');
Route::delete('/events/{id}',[EventController::class,'destroy']);
Route::get('/events/edit/{id}',[EventController::class,'edit'])->middleware('auth');
Route::put('/events/update/{id}',[EventController::class, 'update'])->middleware('auth');
Route::post('/events/join/{id}',[EventController::class, 'jointEvent'])->middleware('auth');
Route::post('/events/leave/{id}', [EventController::class, 'leaveEvent'])->name('event.leave');