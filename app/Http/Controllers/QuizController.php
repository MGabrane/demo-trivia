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
        if($request->session()->get('currentNumber')) {
            $question = $request->session()->get('currentQuestion') ?? '';
            $answerOptions = $request->session()->get('answerOptions') ?? [];
        } else {
            $previousNumbers = $request->session()->get('previousNumbers') ?? [];
            $questionData = $this->quizService->setupQuestion($previousNumbers);

            if(!$questionData) {
                return view('something-wrong');
            }

            $currentNumber = $questionData['number'];
            $question = $questionData['text'];
            $answerOptions = $questionData['answer_options'];

            $request->session()->put('currentNumber', $currentNumber);
            $request->session()->put('currentQuestion', $question);
            $request->session()->put('answerOptions', $answerOptions);
        }


        return view('quiz', ['randomNumbers' => $answerOptions, 'triviaQuestion' => $question]);
    }
}
