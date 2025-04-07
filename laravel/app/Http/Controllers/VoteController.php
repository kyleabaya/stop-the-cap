<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Round;
use App\Models\Vote;


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
        $pointsCorrectVote = 100;
        $pointsforCorrectVoteIfCaught = 200;
        $pointsforImposterIfNotCaught = 300;

        if ($imposterCaught){
            foreach ($votes as $vote){
                if($vote->suspect_id == $round->imposter_id) {
                    $vote->voter->increment('points', $pointsforCorrectVoteIfCaught);
                    }
                }
            } else {
                foreach ($votes as $vote) {
                    if($vote->suspect_id == $round->imposter_id){
                        $vote->voter->increment('points', $pointsforImposterIfNotCaught);
                    }
                }
                $imposter = Player::find($rounds->imposter_id);
                if($imposter){
                    $imposter->increment('points', $pointsforImposterIfNotCaught);
                }
            }
            $round->update(['status' => 'completed']);

        $result = ['voted_suspect_id'=>$votedOutSuspectId, 'vote_count'=>$voteCount];
    }
}
