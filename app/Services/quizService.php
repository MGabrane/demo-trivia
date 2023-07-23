<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use \Exception;

class QuizService {

    private const MIN_QUIZ_NUMBER = 1;
    private const MAX_QUIZ_NUMBER = 1000;

    public function setupQuestion($quizNumbers) {
        $questionData = $this->getQuizData($quizNumbers);

        if(!$questionData) {
            return false;
        }

        $answerOptions = $this->generateOptions($questionData['number']);

        // Return false if there are problems, or necessery data for view
        $setupQuestionData = [
            'number' => $questionData['number'],
            'text' => str_replace($questionData['number'], '???', $questionData['text']),
            'answer_options' => $answerOptions
        ];

        return $setupQuestionData;
    }

    private function getQuizData($quizNumbers) {
        $alreadyHasThisNumber = false;
        while(!$alreadyHasThisNumber) {
            $randomNumber = $this->generateNumber($quizNumbers);
            try {
                $response = Http::get(config('services.numbers_api.url') . '/' . $randomNumber .'/trivia?json&notfound=floor');
            } catch (Exception $e) {
                LOG::error($e->getMessage());
                return false;
            }

            if($response->status() != 200) {
                return false;
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
        for($i = 0; $i < 3; $i++) {
            $options[] = $this->generateNumber($options);
        }
        return $options;
    }

    private function generateNumber($quizNumbers) {
        while(in_array(($number = mt_rand(self::MIN_QUIZ_NUMBER, self::MAX_QUIZ_NUMBER)), $quizNumbers));
        return $number;
    }

}