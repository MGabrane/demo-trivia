<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizService;
use Illuminate\Support\Facades\Log;
use \Exception;

class QuizController extends Controller
{
    public function __construct(private QuizService $quizService) {}

    public function playQuiz(Request $request)
    {
        try {
            $questionData = $this->quizService->setupQuestion($request);
        } catch(Exception $e) {
            LOG::error($e->getMessage());
            return view('something-wrong');
        }

        return view('quiz', ['randomNumbers' => $questionData['answerOptions'], 'triviaQuestion' => $questionData['question']]);
    }

    public function answerQuiz(Request $request) {

        $answerStatus = $this->quizService->answerQuestion($request);

        if($answerStatus === 'finish') {
            return redirect()->route('quizSummary');
        }

        return redirect()->route('playQuiz');
    }

    public function quizSummary(Request $request) {
        if(!$request->session()->has('correctAnswerCount')) {
            return redirect()->route('playQuiz');
        }

        $correctAnswerCount = $request->session()->get('correctAnswerCount');

        if($correctAnswerCount < $this->quizService::QUESTIONS_COUNT) {
            return view('fail', ['correctAnswersCount' => $correctAnswerCount, 'questionCount' => $this->quizService::QUESTIONS_COUNT]);
        }

        return view('success');
    }
}
