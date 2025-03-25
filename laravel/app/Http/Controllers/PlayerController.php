<?php

namespace App\Http\Controllers;
use App\Models\Player;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    //
    public function getPlayers($gameCode)
    {
        $players = Player::where('game_code', $gameCode)->get();
        return response()->json($players);
    }
}
