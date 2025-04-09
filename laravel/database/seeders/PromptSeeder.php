<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromptSeeder extends Seeder
{
    public function run()
    {
        DB::table('prompts')->insert([
            ['prompt_text' => 'Have you ever skipped class?'],
            ['prompt_text' => 'Do you like pineapple on pizza?'],
            ['prompt_text' => 'Have you cheated on a test?'],
            ['prompt_text' => 'Do you think AI will take over the world?'],
            ['prompt_text' => 'Would you survive a zombie apocalypse?'],
            ['prompt_text' => 'Have you ever ghosted someone?'],
            ['prompt_text' => 'Can you do a backflip?'],
            ['prompt_text' => 'Have you ever lied to your best friend?'],
        ]);
    }
}

