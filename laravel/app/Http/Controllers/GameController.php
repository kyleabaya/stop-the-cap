<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Round;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Vote;


class GameController extends Controller

{
    //for /latest-game
     public function latest()
     {
         $latestGame = Game::latest()->first();
         return response()->json(['code' => $latestGame ? $latestGame->code : null, 'id' => $latestGame->id]);
     }

     public function latestGame()
     {
         $latestGame = Game::latest()->first();
         return response()->json($latestGame);
     }
 
     // Generates and store a new game code
     public function create()
     {
        try {    
            $code = strtoupper(Str::random(6));
            $game = Game::create([
                'code' => $code,
                'imposters_remaining' => 4, 
                'status' => 'waiting',
            ]);    
            return response()->json(['code' => $game->code]);
    
        } catch (\Exception $e) {
            Log::error("Error creating game: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create game'], 500);
        }
     }
    // Handle updating the points
    public function awardPoints(Request $request)
    {
        $userId = $request->user()->id;
        $points = $request->input('points');

        // Find or create a player record
        $player = Player::firstOrCreate(['user_id' => $userId]);

        // Increment the points for each player
        $player->increment('points', $points);

        return response()->json(['message' => 'Points awarded!', 'new_points' => $player->points]);
    }

    //Handle the round turns

    public function nextRound(Request $request)
    {
        $userId = $request->user()->id;

        // Find or create a player record
        $player = Player::firstOrCreate(['user_id' => $userId]);

        // Increment the round for each player
        $player->increment('rounds');

        return response()->json(['message' => 'Round incremented!', 'new_rounds' => $player->rounds]);
    }

    //Handle states within the rounds.
    //first from The question, giving a response, chatbox, voting, then next round.
   
    public function updateRoundState(Request $request, $roundId)
    {
        $round = Round::findOrFail($roundId);
        $newState = $request->input('state');

        if (!in_array($newState, ['start', 'ask_question', 'answer_question', 'chat_for_a_min', 'next_round'])) {
            return response()->json(['error' => 'Invalid state'], 400);
        }
        $round->update(['state' => $newState]);

        return response()->json([
            'message' => 'Moving to next state!',
            'round' => $round,
        ]);
    }

    public function revealImposter($game_id)
    {
        $game = Game::findOrFail($game_id);
        $players = $game->players; 
        $votes = Vote::where('game_id', $game_id)->get();

        $voteCounts = $votes->groupBy('voted_for_player_id')->map->count();
        $mostVotedPlayerId = $voteCounts->keys()->max();

        // Find the imposter player
        $imposter = $players->firstWhere('is_imposter', true);
        $isImposterRevealed = ($mostVotedPlayerId == $imposter->id);

        return response()->json([
            'is_imposter_revealed' => $isImposterRevealed,
            'imposter' => $isImposterRevealed ? $imposter : null
        ]);
    }
}


