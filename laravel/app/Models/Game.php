<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\messages;

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

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    protected $casts = [
        'phase' => 'string',
    ];

}
