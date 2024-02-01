<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Word extends Model
{
    use HasFactory;

    public $updated_at = false;

    protected $fillable = [
        'name'
    ];

    public function languages() : BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_meaning_word')->withPivot('meaning_id');
    }

    public function meanings() : BelongsToMany
    {
        return $this->belongsToMany(Meaning::class, 'language_meaning_word')->withPivot('language_id');
    }
}
