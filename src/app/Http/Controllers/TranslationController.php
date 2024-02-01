<?php

namespace App\Http\Controllers;

use App\Models\Meaning;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        #$asd = DB::table('meaning_user')->where('user_id', $user->id)->get();
        $user_languages = $user->languages->sortBy('name')->prepend($user->native_language);
        $meanings = DB::table('meaning_user')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $translations = collect();
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

            $translations->put($meaning->meaning_id, $temp_translations);
        }

        return response(view('translations.index')->with(['languages' => $user_languages,
            'meanings' => $translations->collect(), 'native_language' => $user->native_language]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): \Illuminate\Http\Response
    {
        $user = Auth::user();
        $user_languages = $user->languages->sortBy("name")->prepend($user->native_language);
        return response(view('translations.create')->with(['languages' => $user_languages,
            'native_language' => $user->native_language, 'word_conflict' => false]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: add logic for native language
        // load only languages that this user has
        $user = Auth::user();
        // remove token from array
        $translations = $request->collect()->slice(1);

        // Create meaning, word and assign word to meaning
        $meaning = new Meaning();
        $meaning->save();
        $meaning->users()->attach(Auth::user()->id);
        foreach ($translations as $language_id => $word_name) {
            if (!$language_id) // null
                continue;

            // One word can have many meanings
            $word = Word::firstOrCreate(['name' => $word_name]);

            // create triplet, attach word to corresponding language and meaning
            $meaning->words()->attach($word->id, ['language_id' => $language_id]);
        }
        session(['success' => 'Translation successfully created!']);

        return $this->create();

        // TODO: implement conflicts
        #$lang_word = $this->createLanguageWordFromRequest($request, $user_languages);
        $conflict = false;
        // if not force store, check for conflicts
        if (!$request->force_store) {
            foreach ($user_languages as $language) {
                $language_name = $language->name;
                // check for conflicts (same word in same language)
                $temp_conflict_words = Word::where(['name' => $request->$language_name])
                    ->whereHas('languages', function ($query) use ($language_name) {
                        $query->where('name', $language_name);
                    })->get();
                if (count($temp_conflict_words) != 0)
                    $conflict = true;
            }
        }

        if ($conflict) // conflict results in retrieving meanings with conflict words and returning modal box to user
        {
            $translations = $this->getConflictTranslations($request, $user_languages);
            return view('translations.create')->with(['languages' => $user_languages,
                'word_conflict' => true,
                'meanings' => $translations,
                'translation_ctr' => 1,
                'lang_word' => $lang_word]);
        } else // no conflict results in a new meaning and n-words belonging to it
        {
            $meaning = new Meaning();
            $meaning->save();
            $meaning->users()->attach(Auth::user()->id);
            foreach ($user_languages as $language) {
                $language_name = $language->name;
                if (!$request->$language_name) // null
                    continue;

                $word = Word::firstOrCreate(['name' => $request->$language_name]);
                // create triplet, attach word to corresponding language and meaning
                $word->languages()->attach($language->id, ['meaning_id' => $meaning->id]);
            }
            session(['success' => 'Translation successfully created!']);
        }
        return $this->create();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request ()
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // remove METHOD and token attributes
        $words = collect($request)->slice(2);
        $update_data = [];
        foreach ($words as $word_id => $word_name) {
            $update_data[] = ['id' => $word_id, 'name' => $word_name];
        }
        \Batch::update(new Word, $update_data, 'id');

        session(['success' => 'Successfully assigned!']);
        // back to creation page
        return redirect()->route('translations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Words that ex
     *
     * @param \Illuminate\Http\Request $request
     * @param array Languages  $user_languages
     * @return array Word $conflict_words
     */
    public function getConflictTranslations($request, $user_languages)
    {
        $translations = collect();
        foreach ($user_languages as $user_language) {
            // retrieve meaning_id for conflicting pairs (word_id, language_id)
            $user_language_name = $user_language->name;
            $meanings = DB::table('language_meaning_word')
                ->select('meaning_id')
                ->join('languages', 'languages.id', '=', 'language_meaning_word.language_id')
                ->join('words', 'words.id', '=', 'language_meaning_word.word_id')
                ->where([
                    ['languages.name', $user_language_name],
                    ['words.name', $request->$user_language_name]
                ])
                ->get();

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

                // append translation to corresponding meaning id
                $translations->put($meaning->meaning_id,
                    $this->createLanguageWordFromTranslation($user_languages, $temp_translations));
            }
        }
        return $translations->all();
    }

    /**
     * Words that ex
     *
     * @param $user_languages
     * @param $temp_translations
     * @return array Word $conflict_words
     */
    public function createLanguageWordFromTranslation($user_languages, $temp_translations)
    {
        // create LANGUAGE => WORD array to match empty language translations for frontend modal box
        $lang_word = [];
        $temp_translation_ctr = 0;
        foreach ($user_languages as $user_language) {
            if ($temp_translation_ctr < count($temp_translations) &&
                $user_language->name == $temp_translations[$temp_translation_ctr]->language_name)
                $lang_word[$user_language->name] = $temp_translations[$temp_translation_ctr++]->word_name;
            else // missing user language translation
                $lang_word[$user_language->name] = "";
        }

        return $lang_word;
    }

    /**
     * Words that ex
     *
     * @param $user_languages
     * @param $temp_translations
     * @return array Word $conflict_words
     */
    public function createLanguageWordFromRequest($request, $user_languages)
    {
        // create LANGUAGE => WORD array to match empty language translations for frontend modal box
        $lang_word = [];
        foreach ($user_languages as $user_language) {
            if ($request->input($user_language->name))
                $lang_word[$user_language->name] = $request->input($user_language->name);
            else // missing user language translation
                $lang_word[$user_language->name] = "";
        }
        return $lang_word;
    }

    /**
     * Words that ex
     *
     * @param $user_languages
     * @param $temp_translations
     * @return array Word $conflict_words
     */
    public function checkTranslation(Request $request, $id)
    {
        // TODO: check for synonyms
        dd($request);

        // create LANGUAGE => WORD array to match empty language translations for frontend modal box
        $lang_word = [];
        foreach ($user_languages as $user_language) {
            if ($request->input($user_language->name))
                $lang_word[$user_language->name] = $request->input($user_language->name);
            else // missing user language translation
                $lang_word[$user_language->name] = "";
        }
        return $lang_word;
    }
}
