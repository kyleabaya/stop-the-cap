<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ImposterController;
use Illuminate\Support\Facades\Route;

//getting game code
Route::get('/latest-game', [GameController::class, 'latest']);
Route::post('/new-game', [GameController::class, 'create']);
Route::get('/players/{gameCode}', [PlayerController::class, 'getPlayers']);

