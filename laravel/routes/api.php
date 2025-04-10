<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ImposterController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResponseController;
use App\Models\Round;

//getting game code
Route::get('/latest-game', [GameController::class, 'latest']);//this only returns id and code
Route::get('/latest-game-return-game', [GameController::class, 'latestGame']); // this returns the whole game object 

Route::post('/new-game', '\App\Http\Controllers\GameController@create');
Route::get('/get-code',[PlayerController::class, 'getCode']);

//joining the lobby
Route::post('/join-lobby', [PlayerController::class, 'join']); 

//getting players to be displayed 
Route::get('/getPlayers/{game_id}', [PlayerController::class, 'getPlayers']);
Route::get('/games/{code}/players', [PlayerController::class, 'getPlayers']);

//get the single current player
Route::get('getPlayer/{player_id}', [PlayerController::class, 'getPlayer']);

//for the chatbox
// Route::get('/messages', [MessageController::class, 'fetchMessages']);
Route::get('/messages/{game_id}', [MessageController::class, 'fetchMessages']);
Route::post('/messages/new', [MessageController::class, 'fetchNewMessages']);
Route::post('/messages/send', [MessageController::class, 'sendMessage']);

//prompt receiving

Route::get('/prompts/random', [PromptController::class, 'random']);
Route::get('/prompts/{prompt}', [PromptController::class, 'show']);
Route::get('/prompts/{prompt}', [PromptController::class, 'update']);
Route::get('/prompts/{prompt}', [PromptController::class, 'destroy']);
Route::get('/prompts', [PromptController::class, 'index']);
Route::post('/prompts', [PromptController::class, 'store']);

//response
Route::post('/responses/{round}/store', [ResponseController::class, 'store']);
Route::get('/responses/{game_id}', [ResponseController::class, 'getResponses']);
Route::get('/games/{game_id}/rounds/{round_id}/has-everyone-responded', [ResponseController::class, 'hasEveryoneResponded']);


//votes
Route::post('/rounds/{round}/votes', [VoteController::class, 'store']);
Route::post('/rounds/{round}/tally', [VoteController::class, 'tally']);
Route::get('/games/{game_id}/has-everyone-voted/{round_id?}', [GameController::class, 'hasEveryoneVoted']);


//rounds 
Route::post('rounds/{game}/start-round', [RoundController::class, 'startRound']);
Route::get('/rounds/{game_id}/latest-round', [RoundController::class, 'latestRound']);
Route::post('/rounds/vote', [VoteController::class, 'store']);
Route::post('/rounds/{game_id}/next-phase', [RoundController::class, 'nextPhase']);
Route::get('/rounds/{game_id}/reset-or-continue-imposter', [RoundController::class, 'resetOrContinueImposterRound']);

//imposter
Route::post('/game/{game_id}/reveal-imposter', [GameController::class, 'revealImposter']);
Route::post('game/{game_id}/assign-imposter', [RoundController::class, 'assignImposter']);

Route::get('/games/{game}/imposter-tally', [VoteController::class, 'postImposterTally']);

Route::get('/games/{game}/final-results', [GameController::class, 'finalResults']);

