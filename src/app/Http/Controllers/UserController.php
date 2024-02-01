<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
        //TODO: add check for native language
        $user = Auth::user();
        //TODO: edit seeds
        $user->languages()->detach($user->native_language->id);

        $languages = Language::all();

        $native_language = $user->native_language;
        $native_language['native_language'] = True;

        $user_languages = $user->languages;
        foreach ($user_languages as $user_language)
        {
            $user_language['selected'] = True;
        }

        $languages = $languages->push($native_language)->merge($user_languages);
        return view('profile')->with(['languages' => $languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //TODO: implement gate check or policy instead of this ID check
        $user = Auth::user();

        if ($user->id === intval($id))
        {
            $input = $request->all();

            $user->native_language()->associate($input['native_language_id']);
            $user->save();

            // remove native_language from languages
            /*
            if (($key = array_search($input['native_language_id'], $input['language_ids'])) !== false) {
                unset($input['language_ids'][$key]);
            }
            */
            $user->languages()->sync($input['language_ids']);

            session(['success' => 'Profile successfully updated!']);
        }

        return redirect('profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
