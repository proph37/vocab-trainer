<?php

namespace App\Http\Controllers;

use App\Models\Guess;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    private $max_multiplier;
    private $streak_divider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->max_multiplier = 4;
        $this->streak_divider = 10;
    }

    /**
     * Get the path the user should be redirected to.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return route('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function lastTranslations(Request $request)
    {
        // TODO: request->n required
        $user = Auth::user();
        #$asd = DB::table('meaning_user')->where('user_id', $user->id)->get();
        $user_languages = $user->languages->sortBy('name')->prepend($user->native_language);
        $meanings = DB::table('meaning_user')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($request->n)
            ->get();

        $translations = collect();
        #$translations = [];
        foreach ($meanings as $meaning) {
            // retrieve language_id, language_name, word_id, word_name corresponding to specific meaning_id
            $temp_translations = DB::table('language_meaning_word')
                ->select('languages.id as language_id', 'languages.name as language_name',
                    'words.id as word_id', 'words.name as word_name')
                ->join('languages', 'languages.id', '=', 'language_meaning_word.language_id')
                ->join('words', 'words.id', '=', 'language_meaning_word.word_id')
                ->where('meaning_id', $meaning->meaning_id)
                ->orderBy('language_name', 'asc')
                ->get()
                ->all();

            // Remove 'word_name' only if 'language_name' !== $native_language->name
            foreach ($temp_translations as &$item) {
                if ($item->language_name !== $user->native_language->name) {
                    unset($item->word_name);
                }
            }

            $translations->put($meaning->meaning_id, $temp_translations);
        }

        return response(view('quiz.last_translations')->with(['languages' => $user_languages,
            'meanings' => $translations->collect(), 'native_language' => $user->native_language]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function randomTranslations(Request $request)
    {
        // TODO: request->n required
        $user = Auth::user();
        #$asd = DB::table('meaning_user')->where('user_id', $user->id)->get();
        $user_languages = $user->languages->push($user->native_language)->sortBy('name');
        $meanings = DB::table('meaning_user')
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->limit($request->n)
            ->get();

        $translations = collect();
        #$translations = [];
        foreach ($meanings as $meaning) {
            // retrieve language_id, language_name, word_id, word_name corresponding to specific meaning_id
            $temp_translations = DB::table('language_meaning_word')
                ->select('languages.id as language_id', 'languages.name as language_name',
                    'words.id as word_id', 'words.name as word_name')
                ->join('languages', 'languages.id', '=', 'language_meaning_word.language_id')
                ->join('words', 'words.id', '=', 'language_meaning_word.word_id')
                ->where('meaning_id', $meaning->meaning_id)
                ->orderBy('language_name', 'asc')
                ->get()
                ->all();

            // Remove 'word_name' only if 'language_name' !== $native_language->name
            foreach ($temp_translations as &$item) {
                if ($item->language_name !== $user->native_language->name) {
                    unset($item->word_name);
                }
            }

            $translations->put($meaning->meaning_id, $temp_translations);
        }

        return response(view('quiz.random_translations')->with(['languages' => $user_languages,
            'meanings' => $translations->collect(), 'native_language' => $user->native_language]));
    }

    /**
     * Words that ex
     *
     * @param $user_languages
     * @param $temp_translations
     * @return \Illuminate\Http\Response
     */
    public function checkTranslation($translations, $id)
    {
        // TODO: check for synonyms
        //$translations = collect($request)->slice(1);

        // Check translations and fill guesses accordingly
        $user = Auth::user();
        $words = Word::find($translations->keys());
        $word_ctr = 0;
        $guess_results = collect();
        $all_correct = true;
        foreach ($translations as $word_id => $word_name) {
            if ($words[$word_ctr]->id !== $word_id) {
                // TODO: add exception handling
                dd("Word IDs not matching!");
            }

            // Guess history logic
            $old_guess = Guess::where(['word_id' => $word_id])->latest()->first();


            if ($words[$word_ctr]->name === $word_name) {
                $guess_result = "correct";
            } else {
                $guess_result = "incorrect";
                $all_correct = false;
            }
            $guess_results->put($word_id, $guess_result);

            Guess::create(['word_id' => $word_id,
                           'user_id' => $user->id,
                           'correct' => $guess_result === "correct",
                           'attempt_number' => $old_guess ? $old_guess->attempt_number + 1 : 1]);
            //$guess->save();

            $word_ctr++;
        }

        // User points logic
        if ($all_correct) {
            $user->points += $user->multiplier;
            $user->streak += 1;
            if ($user->multiplier < $this->max_multiplier and
                $user->streak !== 0 and
                $user->streak % $this->streak_divider === 0) {
                $user->multiplier += 1;
            }
        } else {
            $user->points -= 1;
            $user->multiplier = 1;
            $user->streak = 0;
        }

        $user->save();

        return response($guess_results);
    }
}
