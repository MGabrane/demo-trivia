<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use \Exception;

class QuizService {

    private const MIN_QUIZ_NUMBER = 1;
    private const MAX_QUIZ_NUMBER = 1000;
    private const ANSWER_OPTION_COUNT = 4;
    public const QUESTIONS_COUNT = 20;

    public function setupQuestion(Request $request) {

        // Forget correctAnswerCount
        if($request->session()->has('correctAnswerCount')) {
            $request->session()->forget('correctAnswerCount');
            $request->session()->forget('correctAnswer');
        }

        // Initialize new question if necessary
        if(!$request->session()->has('currentNumber')) {
            $this->initializeNextQuestion($request);
        }

        $question = $request->session()->get('currentQuestion') ?? '';
        $answerOptions = $request->session()->get('answerOptions') ?? [];

        $questionData = [
            'question' => $question,
            'answerOptions' => $answerOptions
        ];

        return $questionData;
    }

    private function initializeNextQuestion(Request $request) {
        $previousNumbers = $request->session()->get('previousNumbers') ?? [];

        $questionData = $this->getQuizData($previousNumbers);
        $question = str_replace($questionData['number'], '___', $questionData['text']);
        $answerOptions = $this->generateOptions($questionData['number']);

        $request->session()->put('currentNumber', $questionData['number']);
        $request->session()->put('currentQuestion', $question);
        $request->session()->put('answerOptions', $answerOptions);
    }

    private function getQuizData($quizNumbers) {
        $alreadyHasThisNumber = false;
        while(!$alreadyHasThisNumber) {
            $randomNumber = $this->generateNumberNotIn($quizNumbers);

            $response = Http::get(config('services.numbers_api.url') . '/' . $randomNumber .'/trivia?json&notfound=floor');

            if($response->status() != 200) {
                LOG::error('API request status code ' . $response->status());
                throw new Exception('API did not return success.');
            }

            $quizData = $response->json();
            $returnedNumber = $quizData['number'];
            if(!in_array($returnedNumber, $quizNumbers)) {
                $alreadyHasThisNumber = true;
            }
        }

        return $quizData;
    }

    private function generateOptions($quizNumber) {
        $options = [];
        $options[] = $quizNumber;
        while(count($options) < self::ANSWER_OPTION_COUNT) {
            $options[] = $this->generateNumberNotIn($options);
        }

        return Arr::shuffle($options);
    }

    private function generateNumberNotIn($quizNumbers) {
        while(in_array(($number = mt_rand(self::MIN_QUIZ_NUMBER, self::MAX_QUIZ_NUMBER)), $quizNumbers));
        return $number;
    }

    public function answerQuestion(Request $request) {
        $answer = $request['quiz_answer'];

        $correctAnswer = $request->session()->get('currentNumber');
        $previousNumbers = $request->session()->get('previousNumbers') ?? [];
        $correctAnswerCount = count($previousNumbers);

        if($answer == $correctAnswer) {
            $correctAnswerCount++;
        }

        if(($answer == $correctAnswer && $correctAnswerCount === self::QUESTIONS_COUNT) || $answer != $correctAnswer) {
            $question = $request->session()->get('currentQuestion');
            $correctAnswerText = str_replace('___', $correctAnswer, $question);

            $request->session()->flush();

            $request->session()->put('correctAnswer', $correctAnswerText);
            $request->session()->put('correctAnswerCount', $correctAnswerCount);
            return 'finish';
        }

        $previousNumbers[] = $correctAnswer;
        $request->session()->put('previousNumbers', $previousNumbers);
        $request->session()->forget('currentNumber');
        return 'success';
    }

}