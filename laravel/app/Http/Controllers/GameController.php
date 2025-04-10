<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Round;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Vote;
use App\Models\Response;


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
    // handle updating the points
    public function awardPoints(Request $request)
    {
        $userId = $request->user()->id;
        $points = $request->input('points');
        $player = Player::firstOrCreate(['user_id' => $userId]);
        $player->increment('points', $points);

        return response()->json(['message' => 'Points awarded!', 'new_points' => $player->points]);
    }

    public function nextRound(Request $request)
    {
        $userId = $request->user()->id;
        $player = Player::firstOrCreate(['user_id' => $userId]);
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

    //check to see if everyone has voted 
    public function hasEveryoneVoted($game_id, $round_id = null)
{
    $game = Game::with('players')->findOrFail($game_id);
    $playerIds = $game->players->pluck('id')->toArray();

    // Get the current round using the given round_id or the latest round.
    $round = $round_id 
        ? $game->rounds()->where('id', $round_id)->first() 
        : $game->rounds()->latest()->first();

    if (!$round) {
        return response()->json(['error' => 'Round not found.'], 404);
    }

    // Use the Vote model now (instead of Response) to count votes.
    $voterIds = \App\Models\Vote::where('round_id', $round->id)
                      ->pluck('voter_id')
                      ->unique()
                      ->toArray();

    // Check if every player's ID is in the voterIds array.
    $allVoted = collect($playerIds)->every(function ($id) use ($voterIds) {
        return in_array($id, $voterIds);
    });

    return response()->json([
        'all_voted'   => $allVoted,
        'round_id'    => $round->id,
        'voted_ids'   => $voterIds,
        'expected_ids'=> $playerIds,
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

    public function finalResults($gameId)
    {
    $game = Game::with('players')->findOrFail($gameId);

    $players = $game->players()->select('id', 'name', 'points')->get();

    // Find player with max points
    $winner = $players->sortByDesc('points')->first();

    return response()->json([
        'players' => $players,
        'winner' => $winner,
    ]);
    }
}


