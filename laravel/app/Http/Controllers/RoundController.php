<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Round;
use App\Models\Player;

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
        Player::whereIn('id', [$imposterId])->update(['is_imposter' => true]);
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

    public function nextPhase($game_id)
    {
        $game = Game::findOrFail($game_id);
        $round = $game->rounds()->latest()->first(); 
    
        if (!$round) {
            return response()->json(['error' => 'No round found for this game'], 404);
        }
    
        $phaseOrder = ['lobby', 'prompt_received', 'response', 'chat', 'voting', 'next_round'];
        $currentPhaseIndex = array_search($round->phases, $phaseOrder);
    
        if ($currentPhaseIndex === false || $currentPhaseIndex === count($phaseOrder) - 1) {
            return response()->json(['message' => 'No further phases'], 400);
        }
        // move to next phase
        $round->phases = $phaseOrder[$currentPhaseIndex + 1];
        $round->save();
        return response()->json(['next_phase' => $round->phases]);
    }
    

}
