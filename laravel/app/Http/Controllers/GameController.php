<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Round;

class GameController extends Controller

{
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


    public function startState($gameId)
    {
        // Get the latest round or create a new one
        $currentRound = Round::where('game_id', $gameId)->latest()->first();

        if (!$currentRound || $currentRound->state == 'finished') {
            $newRoundNumber = $currentRound ? $currentRound->round_number + 1 : 1;
            $currentRound = Round::create([
                'game_id' => $gameId,
                'round_number' => $newRoundNumber,
                'state' => 'start',
            ]);
        }

        return response()->json([
            'message' => 'New Round started!',
            'round' => $currentRound,
        ]);
    }

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
}


