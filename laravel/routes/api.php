<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;


Route::get('/latest-game', [GameController::class, 'latest']);
Route::post('/new-game', [GameController::class, 'create']);
