<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ImposterController;
use Illuminate\Support\Facades\Route;

//getting game code
Route::get('/latest-game', [GameController::class, 'latest']);
Route::post('/new-game', '\App\Http\Controllers\GameController@create');
Route::get('/get-code',[PlayerController::class, 'getCode']);

//joining the lobby
Route::post('/join-lobby', [PlayerController::class, 'join']);

//getting players to be displayed 
Route::get('/getPlayers', [PlayerController::class, 'getPlayers']);

Route::get('/getPlayers/{code}', [PlayerController::class, 'getPlayers']);

