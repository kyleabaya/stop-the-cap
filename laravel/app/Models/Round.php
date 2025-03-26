<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'round_number',
        'prompt_id',
        'imposter_round_count',
        'imposter_id',
        'status'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function prompt()
    {
        return $this->belongsTo(Prompt::class);
    }

    public function imposter()
    {
        return $this->belongsTo(Player::class, 'imposter_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
