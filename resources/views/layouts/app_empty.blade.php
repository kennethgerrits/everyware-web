<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Huiswerkapp</title>
    @push('styles')
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @endpush

    @stack('styles')
</head>

<body>
<main class="py-4">
    <div class="container">
        @stack('body')
    </div>
</main>

<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>

</html>
