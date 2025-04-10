<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use Illuminate\Support\Facades\Log;


class RoundController extends Controller
{
    //Start a New Round
    public function startRound(Game $game, Request $request){

        // Get current imposter
        $currentImposter = $game->players()->where('is_imposter', true)->first();
        //assign imposter or keep imposter

        if(!$currentImposter) {
            $imposterPlayerId = $this->assignImposter($game);
            $imposterRoundCount = 1;
        } else {
            $imposterPlayerId = $currentImposter->id;

            $lastRound = $game->rounds()->latest()->first();
            $imposterRoundCount = $lastRound ? $lastRound->imposter_round_count + 1 : 1;
        }

        $prompt = \App\Models\Prompt::inRandomOrder()->first();

        $roundNumber = $game->rounds()->count() +1;

        $round = $game->rounds()->create([
            'round_number'=> $roundNumber, 
            'imposter_id' => $imposterPlayerId, 
            'imposter_round_count' => $imposterRoundCount, 
            'prompt_id' => $prompt->id,
            'status' => 'in_progress', 
            'phases' => 'lobby',]);
        
        return response()->json($round->load('prompt'));
    }

    private function assignImposter(Game $game){
        $playerIds = $game->players->pluck('id')->toArray();
        //randomly assign an Imposter
        $imposterId = $playerIds[array_rand($playerIds)];
        Player::whereIn('id', [$imposterId])->update(['is_imposter' => true]);
        return $imposterId;
    }

    public function latestRound($game_id)
    {
        $latestRound = Round::with('prompt')->where('game_id', $game_id)
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

    //Handles the end of the game
    public function resetOrContinueImposterRound(Request $request)
{
    $game_id = (int) $request->route('game_id');
    $game = Game::with('players')->findOrFail($game_id);
    $prompt = \App\Models\Prompt::inRandomOrder()->first();


    $latestRound = Round::where('game_id', $game->id)
        ->orderByDesc('created_at')
        ->first();

    if (!$latestRound) {
        return response()->json(['error' => 'No rounds found for game.'], 404);
    }

    $currentImposter = Player::where('game_id', $game->id)
        ->where('is_imposter', true)
        ->first();

    if (!$currentImposter) {
        return response()->json(['error' => 'No imposter assigned in this game.'], 404);
    }

    if ($latestRound->imposter_round_count > 3) {
        $currentImposter->is_imposter = false; 
        $currentImposter->save();

        $newImposter = Player::where('game_id', $game->id)
            ->where('id', '!=', $currentImposter->id)
            ->inRandomOrder()
            ->first();

        $newImposter->is_imposter = true;
        $newImposter->save();
        // Check if there are imposters remaining
        if ($game->imposters_remaining <= 0) {
            return response()->json([
                'message' => 'No imposters remaining. Game is OVER.',
                'game_id' => $game->id,
                'gameOver' => true,
            ]);
        }
        //if there are imposters remaining, decrement the imposters_remaining
        $game->decrement('imposters_remaining');

        $newRound = Round::create([
            'game_id' => $game->id,
            'round_number' => $latestRound->round_number + 1,
            'imposter_id' => $newImposter->id,
            'imposter_round_count' => 1,
            'prompt' => $prompt,
            'prompt_id' =>$prompt->id,
        ]);

        return response()->json([
            'message' => 'Imposter changed. New round started. Also decremented # of imposters remaining',
            'new_round' => $newRound,
            'new_imposter' => $newImposter,
            'prompt_id' =>$prompt->id,
            'newRound' => true,
        ]);
    }

    // otherwise, continue with same imposter but still make a new round

    $newRound = Round::create([
        'game_id' => $game->id, //same game
        'round_number' => $latestRound->round_number + 1, //new round number
        'imposter_id' => $currentImposter->id, //keep the same imposter
        'imposter_round_count' => $latestRound->imposter_round_count +1, // increment imposter round count
        'prompt_id' =>$prompt->id,

    ]);

    return response()->json([
        'message' => 'Same imposter, game continues to next round of that imposter.',
        'new_round' => $newRound,
        'updated_round' => $latestRound,
        'prompt_id' =>$prompt->id,
    ]);
}
};

