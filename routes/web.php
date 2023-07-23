<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;


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

Route::get('/', function () {
    return view('home');
});

Route::get('/quiz', [QuizController::class, 'playQuiz'])->name('playQuiz');

Route::post('/quiz', [QuizController::class, 'answerQuiz'])->name('answerQuiz');

Route::get('/fail', function() {
    $correctAnswerCount = session()->get('correctAnswerCount');
    if(session()->has('correctAnswerCount')) {
        return view('fail', ['correctAnswersCount' => $correctAnswerCount]);
    } else {
        return redirect()->route('playQuiz');
    }
})->name('failQuiz');

Route::get('/success', function() {
    if(session()->has('correctAnswerCount')) {
        return view('success');
    } else {
        return redirect()->route('playQuiz');
    }
})->name('successQuiz');