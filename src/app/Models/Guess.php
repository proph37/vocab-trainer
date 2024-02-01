<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    use HasFactory;

    public $updated_at = false;

    protected $fillable = [
        'correct',
        'attempt_number',
        'word_id',
        'user_id'
    ];
}
