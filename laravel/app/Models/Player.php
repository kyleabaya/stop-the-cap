<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players';

    protected $fillable = [
        'game_id',
        'name',
        'is_imposter',
        'points',
        'is_ai'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // If you want to see which votes the player cast
    public function votesCast()
    {
        return $this->hasMany(Vote::class, 'voter_id');
    }

    // Votes where this player is the suspect
    public function votesAgainst()
    {
        return $this->hasMany(Vote::class, 'suspect_id');
    }

}
