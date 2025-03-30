<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;

Route::post('/games', [GameController::class, 'create']);
Route::get('/games/{game}', [GameController::class, 'show']);
Route::post('/games/{game}/start', [GameController::class, 'start']);
Route::post('/games/{game}/end', [GameController::class, 'end']);
Route::get('/games/latest', [GameController::class, 'latest']);

Route::get('/games/{code}/players', [PlayerController::class, 'getPlayers']);
Route::post('/players/join', [PlayerController::class, 'join']);
Route::get('/players/get-code', [PlayerController::class, 'getCode']); // optional
Route::post('/players/award-points', [PlayerController::class, 'awardPoints']);

Route::post('/games/{game}/rounds/start', [RoundController::class, 'startRound']);
Route::post('/rounds/{round}/end', [RoundController::class, 'endRound']);
Route::get('/rounds/{round}', [RoundController::class, 'show']);
Route::post('/rounds/{round}/update-state', [RoundController::class, 'updateRoundState']);

Route::post('/rounds/{round}/responses', [ResponseController::class, 'store']);

Route::post('/rounds/{round}/votes', [VoteController::class, 'store']);
Route::post('/rounds/{round}/tally', [VoteController::class, 'tally']);

Route::get('/prompts', [PromptController::class, 'index']);
Route::post('/prompts', [PromptController::class, 'store']);
Route::get('/prompts/random', [PromptController::class, 'random']);
Route::get('/prompts/{prompt}', [PromptController::class, 'show']);
Route::put('/prompts/{prompt}', [PromptController::class, 'update']);
Route::delete('/prompts/{prompt}', [PromptController::class, 'destroy']);