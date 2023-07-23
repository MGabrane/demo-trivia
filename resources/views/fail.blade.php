@extends('layouts.main')

@section('content')
    <div class="fail-content">
        <h2>{{ __('Incorrect answer!'); }}</h2>
        <p>{{ __('Correct answer count - ') . $correctAnswersCount . '/' . $questionCount; }}</p>
        <p>{{ __('Correct answer was: ') }} <b> {{ $correctAnswer  }} </b></p>
        @include('partials.play-quiz-btn')
    </div>
@endsection