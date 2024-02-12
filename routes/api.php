<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlaceController;

Route::get('/places', [PlaceController::class, 'index']);
Route::get('/places/{id}', [PlaceController::class, 'show']);
Route::post('/places', [PlaceController::class, 'store']);
Route::put('/places/{id}', [PlaceController::class, 'update']);
Route::delete('/places/{id}', [PlaceController::class, 'destroy']);

