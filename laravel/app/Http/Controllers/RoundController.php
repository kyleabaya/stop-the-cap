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

        $round = $game->rounds()->create(['round_number'=> $roundNumber, 'imposter_id' => $imposterPlayerId, 'imposter_round_count' => 1, 'status' => 'in_progress', 'phases' => 'lobby']);
        
        return response()->json($round);
    }

    private function assignImposter(Game $game){
        $playerIds = $game->players->pluck('id')->toArray();
        //Randomly assign an Imposter
        $imposterId = $playerIds[array_rand($playerIds)];
        return $imposterId;
    }

    public function latestRound($game_id)
    {
        $latestRound = Round::where('game_id', $game_id)
                            ->orderBy('created_at', 'desc')
                            ->first(); 
    
        if (!$latestRound) {
            return response()->json(['message' => 'No rounds found for this game'], 404);
        }
    
        return response()->json($latestRound);
    }

    public function nextPhase(Request $request)
    {
            $request->validate([
            'game_id' => 'required|exists:games,id',  
        ]);

        // Get the game by game_id
        $game = Game::findOrFail($request->input('game_id'));
        $phaseOrder = ['lobby', 'prompt_received', 'response', 'chat', 'voting', 'next_round'];
        $currentPhaseIndex = array_search($game->phase, $phaseOrder);

        if ($currentPhaseIndex === false || $currentPhaseIndex == count($phaseOrder) - 1) {
            return response()->json(['error' => 'Cannot move to the next phase. perhaps you are already at the last phase.']);
        }
        // get the next phase
        $nextPhase = $phaseOrder[$currentPhaseIndex + 1];
        $game->phase = $nextPhase;
        $game->save();
        return response()->json(['game' => $game]);
    }

}
