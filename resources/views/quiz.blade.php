@extends('layouts.main')

@section('content')
    <div class="quiz-page-content">
        <p><b>{{ $quizQuestion }}</b></p>
        <form class="quiz-form" action="{{ route('answerQuiz') }}" method="POST">
            @csrf
            @foreach ($randomNumbers as $number)
                <div class="input-wrapper">
                    <input type="radio" id="number-{{ $number }}" name="quiz_answer" value="{{ $number }}" required>
                    <label for="number-{{ $number }}">{{ $number }}</label>
                </div>
            @endforeach
            <button class="btn" type="submit">{{ __('Submit'); }}</button>
        </form>
    </div>
@endsection