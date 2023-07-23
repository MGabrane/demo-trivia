@extends('layouts.main')

@section('content')
    <div class="success-content">
        <h2>{{ __('Congratulation, you answered all questions!'); }}</h2>
        @include('partials.play-quiz-btn')
    </div>
@endsection