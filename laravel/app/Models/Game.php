<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'imposters_remaining',
        'status',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Example method to check if game is finished
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

}
