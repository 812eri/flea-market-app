<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'FreeMarket')</title>

    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/items.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/users.css') }}">

    @yield('css')
</head>

<body>
    <div id="app" class="l-page-wrapper">
        <x-app.header />

        <main class="l-main-content">
            @if (session('success'))
                <div class="alert-message alert-message--success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
            <div class="alert-message alert-message--error">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
</html>