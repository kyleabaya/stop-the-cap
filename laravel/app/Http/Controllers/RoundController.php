<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Round;

class RoundController extends Controller
{
    //Start a New Round
    public function startRound(Game $game, Request $request){
        //assign imposter or keep imposter
        if ($game->status !== 'in_progress') 
        {
            return response()->json(['error' => 'Game not in progress.'], 400);
        }
        $imposterPlayerId = $this->assignImposter($game);
        
        $roundNumber = $game->rounds()->count() +1;

        $round = $game->rounds()->create(['round_number'=> $roundNumber, 'imposter_id' => $imposterPlayerId, 'imposter_round_count' => 1, 'status' => 'in_progress' ]);
        
        return response()->json($round);
    }

    public function endRound(Round $round)
    {
        if ($round->status === 'completed') {
            return response()->json(['error' => 'Round is already completed.'], 400);
        }

        $round->update(['status' => 'completed']);
        return response()->json($round);
    }

    public function show(Round $round)
    {
        return response()->json($round);
    }

    private function assignImposter(Game $game){
        $playerIds = $game->players->pluck('id')->toArray();
        //Randomly assign an Imposter
        $imposterId = $playerIds[array_rand($playerIds)];
        return $imposterId;
    }


}
