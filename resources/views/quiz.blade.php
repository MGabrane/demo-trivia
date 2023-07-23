@extends('layouts.main')

@section('content')
    <div class="quiz-page-content">
        <p><b>{{ $quizQuestion }}</b></p>
        <form action="{{ route('answerQuiz') }}" method="POST">
            @csrf
            @foreach ($randomNumbers as $number)
                <input type="radio" id="number-{{ $number }}" name="quiz_answer" value="{{ $number }}" required>
                <label for="number-{{ $number }}">{{ $number }}</label><br>
            @endforeach
            <button type="submit">{{ __('Submit'); }}</button>
        </form>
    </div>
@endsection