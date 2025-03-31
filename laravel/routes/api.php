<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ImposterController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\MessageController;
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

//for the chatbox
// Route::get('/messages', [MessageController::class, 'fetchMessages']);
Route::get('/messages/{game_id}', [MessageController::class, 'fetchMessages']);
Route::post('/messages/new', [MessageController::class, 'fetchNewMessages']);
Route::post('/messages/send', [MessageController::class, 'sendMessage']);

//prompt receiving
Route::get('/prompts', [PromptController::class, 'index']);
Route::post('/prompts', [PromptController::class, 'store']);
Route::get('/prompts/{prompt}', [PromptController::class, 'show']);
Route::get('/prompts/random', [PromptController::class, 'random']);
Route::get('/prompts/{prompt}', [PromptController::class, 'update']);
Route::get('/prompts/{prompt}', [PromptController::class, 'destroy']);

//response
Route::post('/responses/store', [PromptController::class, 'store']);

//votes
Route::post('/rounds/{round}/votes', [VoteController::class, 'store']);
Route::post('/rounds/{round}/tally', [VoteController::class, 'tally']);


