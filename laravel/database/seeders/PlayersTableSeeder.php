<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('players')->insert([
            'name' => 'John Doe',
            'game_id' => 1,  // Use an existing game ID
            'is_imposter' => false,
            'points' => 0,
            'is_ai' => false,
        ]);
    }
}
