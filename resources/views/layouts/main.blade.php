<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('Demo - Trivia'); }}</title>
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>

        @include('partials.header')

        <main>
            <div class="container">
                @yield('content')
            </div>
        </main>

    </body>
</html>
