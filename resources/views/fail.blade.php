@extends('layouts.main')

@section('content')
    <h2>{{ __('Correct answer count - ') . $correctAnswersCount . '/' . $questionCount }}</h2>
    @include('partials.play-quiz-btn')
@endsection