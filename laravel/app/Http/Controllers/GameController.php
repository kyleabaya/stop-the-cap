<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Round;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class GameController extends Controller

{
    //for /latest-game
    public function latest()
     {
         $latestGame = Game::latest()->first();
         return response()->json(['code' => $latestGame ? $latestGame->code : null]);
     }
 
     // Generates and store a new game code
    public function create()
     {
        try {    
            $code = strtoupper(Str::random(6));
            $game = Game::create([
                'code' => $code,
                'imposters_remaining' => 4, 
                'status' => 'waiting',
            ]);    
            return response()->json(['code' => $game->code]);
    
        } catch (\Exception $e) {
            Log::error("Error creating game: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create game'], 500);
        }
     }

    public function show(Game $game)
    {
        return response()->json($game);
    }

    public function start(Game $game)
    {
        if ($game->status !== 'waiting') {
            return response()->json(['error' => 'Game is not in a valid state to start'], 400);
        }
        $game->update(['status' => 'in_progress']);

        return response()->json($game);
    }

    public function end(Game $game)
    {
        if ($game->status === 'completed') {
            return response()->json(['error' => 'Game already ended'], 400);
        }

        $game->update(['status' => 'completed']);

        return response()->json($game);
    }


}


