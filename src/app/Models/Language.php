<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function meanings()
    {
        return $this->belongsToMany('App\Meaning', 'language_meaning_word')->withPivot('meaning_id');
    }

    public function words()
    {
        return $this->belongsToMany('App\Word', 'language_meaning_word')->withPivot('user_id');
    }
}
