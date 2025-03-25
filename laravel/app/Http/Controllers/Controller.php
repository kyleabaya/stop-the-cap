<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Player;

abstract class Controller
{
    //retrieve the players by the game code of the game they are in
    public function getPlayers($gameCode)
    {
        $players = Player::where('game_code', $gameCode)->get();
        return response()->json($players);
    }
}
