<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meaning extends Model
{
    use HasFactory;

    public $updated_at = false;

    public function languages() : BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_meaning_word')->withPivot('word_id');
    }

    public function words() : BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'language_meaning_word')->withPivot('language_id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
