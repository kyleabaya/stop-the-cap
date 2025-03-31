<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt_text',
        'prompt_type'
    ];

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }
}
