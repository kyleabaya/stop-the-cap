<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\Round;
use Illuminate\Support\Facades\Log;

class ResponseController extends Controller
{
    public function store($round_id, Request $request)
{
    $request->validate([
        'player_id'   => 'required|exists:players,id',
        'raised_hand' => 'required|boolean',
    ]);

    $round = Round::findOrFail($round_id);
    $existingResponse = Response::where('round_id', $round->id)
        ->where('player_id', $request->player_id)
        ->first();

    if ($existingResponse) {
        return response()->json([
            'error' => 'Player has already submitted a response for this round.',
        ]); 
    }
    $response = Response::create([
        'round_id'    => $round->id,
        'player_id'   => $request->player_id,
        'raised_hand' => $request->raised_hand,
    ]);
    return response()->json($response);
}

    public function getResponses($game_id)
    {
        $responses = Response::whereHas('round', function ($query) use ($game_id) {
            $query->where('game_id', $game_id);
        })
        ->with(['player:id,name', 'round'])
        ->orderBy('created_at', 'asc')
        ->get(['id', 'raised_hand', 'player_id', 'round_id', 'created_at']);

        return response()->json($responses);
    }
}
