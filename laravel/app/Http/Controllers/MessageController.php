<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Prompt;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MessageController extends Controller
{
    // Fetch all messages
    public function fetchMessages($game_id)
    {   
        $messages = Message::where('game_id', $game_id)
        ->with('player') 
        ->orderBy('created_at', 'asc')
        ->get();
        return response()->json($messages);
    }

    public function cleanup($game_id)
    {
        Message::where('game_id', $game_id)->delete();
        return response()->json(['message' => 'Messages deleted.']);
    }

    // Fetch only new messages after a given timestamp
    public function fetchNewMessages(Request $request)
    {
        $lastMessageTime = $request->input('last_message_time');
        $messages = Message::where('created_at', '>', $lastMessageTime)->latest()->get();
        return response()->json($messages);
    }

    // Store a new message
    public function sendMessage(Request $request)
    {
        $request->validate(['content' => 'required|string',
                            'game_id' => 'required|exists:games,id',
                            'player_id' => 'required|exists:players,id',]);

        $message = Message::create(['content' => $request->input('content'),
                                    'game_id' => $request->input('game_id'),
                                    'player_id' => $request -> input('player_id'),]);

        return response()->json(['success' => true, 'message' => $message]);
    }

}
