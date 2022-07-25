<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Huiswerkapp</title>
    @push('styles')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    @endpush

    @stack('styles')
</head>

<body>
    @include('layouts.partials.navbar')
    <main class="py-4">
        <div class="materials-bg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="materials-bg"></div>

                <div class="col">
                    @if(session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div><br>
                    @elseif(session()->has('error'))
                    <div class="alert alert-danger">{{session()->get('error')}}</div><br>
                    @endif
                </div>
            </div>
            @stack('body')
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    @yield('scripts')
</body>

</html>