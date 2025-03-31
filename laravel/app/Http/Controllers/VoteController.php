<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Round;
use App\Models\Vote;


class VoteController extends Controller
{
    //
    public function store(Round $round, Request $request){
        $vote = Vote::create(['round_id' => $round->id, 'voter_id'=> $request ->input('voter_id'), 'suspect_id'=> $request->input('suspect_id'),]);
        return response()->json($vote);

    }

    public function tally(Round $round) {
        $votes = $round->votes;
        $voteCount = $votes->groupBy('suspect_id')->map->count();
        $votedOutSuspectId = $voteCounts->sortDesc()->keys()->first();
        $result = ['voted_suspect_id'=>$votedOutSuspectId, 'vote_count'=>$voteCount];
    }
}
