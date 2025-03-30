<?php

namespace App\Http\Controllers;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    //getcode from database 

    public function getCode ()
    {
        $game = Game::latest()->first();
        return response()->json(['code' => $game ? $game->code : null]);
    }

    public function getPlayers($code)
    {
        $game = Game::where('code', $code)->first();
        if (!$game) { return response()->json(['error' => 'Game not found'], 404);
        }

        $players = Player::where('game_id', $game->id)->get();
        
        return response()->json(['players' => $players]);
    }

    public function join(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string',
        ]);

    // check if the game exists with the given code
    $game = Game::where('code', $validated['code'])->first();

    if (!$game) {
        return response()->json(['error' => 'Game not found'], 404);
    }

    // if ($game->status !== 'waiting') {
    //         return response()->json(['error' => 'Game has already started'], 400);
    //     }
    
    // create the player in the table
        $player = Player::create([
            'game_id' => $game->id,
            'name' => $request->input('name', 'Anonymous'),
            'is_imposter' => false,
            'points' => 0,
            'is_ai' => false,
        ]);

        return response()->json($player, 201);
    }

    public function awardPoints(Request $request)
    {
        $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'points'    => 'required|integer|min:0'
        ]);
    
        // 2. Find the existing player by their ID
        $player = Player::findOrFail($request->input('player_id'));
    
        // 3. Increment their points
        $player->increment('points', $request->input('points'));
    
        return response()->json([
            'message'    => 'Points awarded!',
            'new_points' => $player->points
        ], 200);
    }

}
