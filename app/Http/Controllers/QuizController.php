<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizService;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{

    public function __construct(private QuizService $quizService) {}

    public function playQuiz(Request $request)
    {
        $previousNumbers = $request->session()->get('quizNumbers') ?? [];

        $questionData = $this->quizService->setupQuestion($previousNumbers);

        if(!$questionData) {
            return view('something-wrong');
        }

        $quizNumbers = $previousNumbers;
        $quizNumbers[] = $questionData['number'];
        $request->session()->put('quizNumbers', $quizNumbers);
        $request->session()->put('answerOptions', $questionData['answer_options']);

        return view('quiz', ['randomNumbers' => $questionData['answer_options'], 'triviaQuestion' => $questionData['text']]);
    }
}
