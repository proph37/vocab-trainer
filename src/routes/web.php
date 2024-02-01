<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

//Auth::routes();
Auth::routes(['register' => false]);

/*Route::group(['prefix' => 'management', 'middleware' => ['auth', 'check.admin.role']], function() {
    Route::resource('/users', 'UserController');
});*/

Route::resource('/translations', TranslationController::class);
//Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
//Route::post('/translations', [TranslationController::class, 'create'])->name('translations.create');
//Route::patch('/translations/{id}', [TranslationController::class, 'update'])->name('translations.update');
//Route::delete('/translations/{id}', [TranslationController::class, 'destroy'])->name('translations.destroy');

Route::get('/quiz/random-translations', [QuizController::class, 'randomTranslations'])->name('quiz.random_translations');
Route::get('/quiz/last-translations', [QuizController::class, 'lastTranslations'])->name('quiz.last_translations');
Route::post('/quiz/{id}', [QuizController::class, 'checkTranslation'])->name('quiz.check');

Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
Route::put('/profile/{id}',  [UserController::class, 'update'])->name('profile.update');

