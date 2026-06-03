<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <script>try { document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark') } catch {}</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'NexusMart') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|playfair-display:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @routes
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
