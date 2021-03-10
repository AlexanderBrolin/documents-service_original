<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <toast-notifications></toast-notifications>
        <?php
            $user = \Illuminate\Support\Facades\Auth::user();
        ?>
        @if(\Illuminate\Support\Facades\Request::route()->getName() !== 'login')
            <header-component :auth-data="{{ json_encode($user) }}"></header-component>
        @endif
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        window.csrfToken = <?php echo json_encode(csrf_token()); ?>
    </script>
</body>
</html>
