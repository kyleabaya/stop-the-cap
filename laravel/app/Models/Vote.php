<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'voter_id',
        'suspect_id',
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function voter()
    {
        return $this->belongsTo(Player::class, 'voter_id');
    }

    public function suspect()
    {
        return $this->belongsTo(Player::class, 'suspect_id');
    }
}
