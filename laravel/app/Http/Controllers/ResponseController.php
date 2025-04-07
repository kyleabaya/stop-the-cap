<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\Round;
use Illuminate\Support\Facades\Log;

class ResponseController extends Controller
{
    public function store(Round $round, Request $request){
    
        $request->validate(['player_id'=> 'required|exists:players,id', 'raised_hand' => 'required|boolean',]);

        // if ($round->status !== 'in_progress'){
        //     return response()->json((['error' => 'Rounds is not in progress']));
        // }
        Log::info('Incoming data', [
            'round_id' => $round->id,
            'player_id' => $request->player_id,
            'raised_hand' => $request->raised_hand,
        ]);
        
        dd([
            'round_id' => $round->id,
            'player_id' => $request->player_id,
            'raised_hand' => $request->raised_hand,
        ]);

        $response = Response::create(['round_id'=> $round->id,'player_id'=> $request->player_id,'raised_hand'=> $request->raised_hand,]);
        
        return response()->json($response);
    }

    public function getResponses($game_id)
    {
        $responses = Response::where('game_id', $game_id)
            ->join('players', 'responses.player_id', '=', 'players.id')
            ->select('responses.id', 'responses.raised_hand', 'players.name as player_name', 'responses.created_at')
            ->orderBy('responses.created_at', 'asc')
            ->get();

        return response()->json($responses);
    }
}
