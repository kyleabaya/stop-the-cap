<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Round;
use App\Models\Vote;
use App\Models\Player;
use App\Models\Game;


class VoteController extends Controller
{

    public function store(Request $request)
{
    // Validate the input
    $request->validate([
        'voter_id'   => 'required|integer|exists:players,id',
        'suspect_id' => 'required|integer|exists:players,id',
        'game_id'    => 'required|integer|exists:games,id', 
        'round_id'   => 'required|integer|exists:rounds,id',
    ]);

    // Create the vote
    $vote = Vote::create([
        'round_id'   => $request->input('round_id'),
        'voter_id'   => $request->input('voter_id'),
        'suspect_id' => $request->input('suspect_id'),
        'game_id'    => $request->input('game_id'), 
    ]);

    return response()->json($vote);
}


    public function tally(Round $round) {
        $votes = $round->votes;
        $voteCount = $votes->groupBy('suspect_id')->map->count();
        $votedOutSuspectId = $voteCount->sortDesc()->keys()->first();
        $imposterCaught = ($votedOutSuspectId == $round->imposter_id);
        
        //point system
        $pointsIfNotCaught = 100;
        $pointsIfCaught = 200;
        $pointsforImposterIfNotCaught = 300;


        foreach ($votes as $vote) {
            if ($vote->suspect_id == $round->imposter_id) {
                $vote->voter->increment('points', $imposterCaught ? $pointsIfCaught : $pointsIfNotCaught);
            }
        }
    
        if (!$imposterCaught) {
            $imposter = Player::find($round->imposter_id);
            if ($imposter) {
                $imposter->increment('points', $pointsForImposterIfNotCaught);
            }
        }
    
        $round->update(['status' => 'completed']);
    
        return response()->json([
            'voted_suspect_id' => $votedOutSuspectId,
            'imposter_caught' => $imposterCaught,
            'vote_count' => $voteCount,
            'imposter_id' => $round->imposter_id,
            'round_id' => $round->id,
        ]);
    }


    public function postImposterTally($gameId)
    {
        $game = Game::with('players')->findOrFail($gameId);

        $latestRound = $game->rounds()->latest()->first();
 
        return response()->json([
            'message' => 'Tally after imposter caught',
            'players' => $game->players()->select('id', 'name', 'points', 'is_imposter')->get(),
            'imposters_remaining' => $game->imposters_remaining,
        ]);
    }

}
