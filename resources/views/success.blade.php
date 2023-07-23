@extends('layouts.main')

@section('content')
    <h2>{{ __('Congratulation, you answered all questions!'); }}</h2>
    @include('partials.play-quiz-btn')
@endsection