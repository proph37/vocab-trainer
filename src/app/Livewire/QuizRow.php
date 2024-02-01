<?php

namespace App\Livewire;

use App\Http\Controllers\QuizController;
use Livewire\Component;

class QuizRow extends Component
{
    public $meaning_id;
    public $translations = [];
    public $guesses = [];
    public $guess_results = [];

    // TODO: add check for already guessed to prevent JS tampering

    public function checkTranslation()
    {
        $response = (new QuizController())->checkTranslation(collect($this->guesses), $this->meaning_id);
        $this->guess_results = $response->original;
    }

    public function render()
    {
        return view('livewire.quiz-row');
    }
}
