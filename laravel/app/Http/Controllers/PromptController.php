<?php

namespace App\Http\Controllers;
use App\Models\Prompt; 
use Illuminate\Http\Request;

class PromptController extends Controller
{

    public function index()
    {
        return response()->json(Prompt::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['prompt_text' => 'required|string', 'prompt_type' => 'required|string',
        'prompt_type'=>'required|string']);
        $prompt = Prompt::create($validated);
        return response()->json($prompt, 201);
    }

    public function show(Prompt $prompt)
    {
        return response()->json($prompt);
    }

    public function update(Request $request, Prompt $prompt)
    {
        $validated = $request->validate([
            'prompt_text' => 'sometimes|string',
            'prompt_type' => 'sometimes|string',
        ]);

        $prompt->update($validated);
        return response()->json($prompt);
    }

    public function destroy(Prompt $prompt)
    {
        $prompt->delete();
        return response()->json(['message' => 'Prompt deleted successfully']);
    }

    public function random()
    {
        $prompt = \App\Models\Prompt::inRandomOrder()->first();

        if (!$prompt) {
            return response()->json(['error' => 'No prompts available']);
        }

        return response()->json($prompt);
    }
}
