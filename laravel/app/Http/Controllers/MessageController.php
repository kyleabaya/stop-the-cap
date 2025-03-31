<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\messages;
use App\Models\prompts;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    // Fetch all messages
    public function fetchMessages($game_id)
    {
        $messages = messages::where('game_id', $game_id)
        ->with('player') 
        ->orderBy('created_at', 'asc')
        ->get();
        return response()->json($messages);
    }

    // Fetch only new messages after a given timestamp
    public function fetchNewMessages(Request $request)
    {
        $lastMessageTime = $request->input('last_message_time');
        $messages = messages::where('created_at', '>', $lastMessageTime)->latest()->get();
        return response()->json($messages);
    }

    // Store a new message
    public function sendMessage(Request $request)
    {
        $request->validate(['content' => 'required',
                            'game_id' => 'required|exists:games,id']);

        $message = messages::create(['content' => $request->input('content'),
                                    'game_id' => $request->input('game_id')]);

        return response()->json(['success' => true, 'message' => $message]);
    }

}
