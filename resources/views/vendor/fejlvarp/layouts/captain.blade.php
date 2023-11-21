<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title?? config('app.name') }}</title>
        @vite(['resources/css/app.css'])
    </head>

    <body>
        <header>
            <nav>
                <a href="/"><img alt="Logo" src="/img/TorbenIT01-sing.png" height="70"></a>
                <ul>
                    <li><a href="/dns-records/create" @class(['current' => request()->is('dns-records/create')])>Create DNS record</a></li>
                    <li><a href="/dns-records" @class(['current' => request()->is('dns-records')])>List DNS records</a></li>
                    <li><a href="/incidents" @class(['current' => request()->is('incidents')])>List site errors</a></li>
                </ul>
            </nav>
            <h1>{{ $title?? config('app.name') }}</h1>
        </header>
        <main>
            @yield('content')
        </main>
        <footer>
            <hr>
            <p>
                Created By
                <a href="https://github.com/tvup">Torben IT ApS</a>
            </p>
        </footer>
    </body>
</html>
