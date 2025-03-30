<?php

namespace App\Http\Controllers;

use App\Models\prompts;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    //
    public function index()
    {
        return prompts::all();
    }

    public function store(Request $request)
    {
        $prompt = prompts::create($request->only(['prompt_text','prompt_type']));
        return response()->json($prompt, 201);
    }

}
