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
        if($request->session()->has('correctAnswerCount')) {
            $request->session()->forget('correctAnswerCount');
        }

        if($request->session()->has('currentNumber')) {
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

    public function answerQuiz(Request $request) {
        $answer = $request['quiz_answer'];
        $correctAnswer = $request->session()->get('currentNumber');
        $previousNumbers = $request->session()->get('previousNumbers') ?? [];
        $correctAnswerCount = count($previousNumbers);

        if($answer == $correctAnswer && $correctAnswerCount == 19) {
            $request->session()->flush();
            $request->session()->put('correctAnswerCount', $correctAnswerCount);
            return redirect()->route('successQuiz');
        } elseif($answer != $correctAnswer) {
            $request->session()->flush();
            $request->session()->put('correctAnswerCount', $correctAnswerCount);
            return redirect()->route('failQuiz');
        } else {
            $previousNumbers[] = $correctAnswer;
            $request->session()->put('previousNumbers', $previousNumbers);
            $request->session()->forget('currentNumber');
        }

        return redirect()->route('playQuiz');
    }
}
