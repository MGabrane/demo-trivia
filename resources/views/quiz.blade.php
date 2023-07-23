@extends('layouts.main')

@section('content')
    <div class="quiz-page-content">
        <p><i>Replace ??? with one of the numbers</i></p>
        <p><b>{{ $triviaQuestion }}</b></p>
    
        <form method="POST">
            @csrf
            @foreach ($randomNumbers as $number)
                <input type="radio" id="number-{{ $number }}" name="quiz_answer" value="{{ $number }}" required>
                <label for="number-{{ $number }}">{{ $number }}</label><br>
            @endforeach
            <button type="submit">{{ __('Submin the answer'); }}</button>
        </form>
    </div>
@endsection