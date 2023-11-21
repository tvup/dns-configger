<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body>
<nav>
    <a href="/dns-records" @class(['current' => request()->is('dns-records')])>DNS records</a>
    <a href="/dns-records/create" @class(['current' => request()->is('dns-records/create')])>Create DNS record</a>
</nav>

{{ $slot }}
</body>
</html>