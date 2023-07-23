@extends('layouts.main')

@section('content')
    <div class="home-page-content">
        <a class="btn" href="{{ url('/quiz') }}">{{ __('Play Quiz'); }}</a>
    </div>
@endsection