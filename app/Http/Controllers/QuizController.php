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

        if($answerStatus === 'finished') {
            return redirect()->route('successQuiz');
        } elseif($answerStatus === 'failed') {
            return redirect()->route('failQuiz');
        }

        return redirect()->route('playQuiz');
    }
}
