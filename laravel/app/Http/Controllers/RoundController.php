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
        $imposterPlayerId = $this->assignImposter($game);
        
        $roundNumber = $game->rounds()->count() +1;

        $round = $game->rounds()->create(['round_number'=> $roundNumber, 'imposter_id' => $imposterPlayerId, 'imposter_round_count' => 1, 'status' => 'in_progress' ]);
        
        return response()->json($round);
    }

    private function assignImposter(Game $game){
        $playerIds = $game->players->pluck('id')->toArray();
        //Randomly assign an Imposter
        $imposterId = $playerIds[array_rand($playerIds)];
        return $imposterId;
    }
}
