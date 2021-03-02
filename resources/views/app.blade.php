<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
        <link rel="android-chrome-icon" type="image/png" sizes="192x192" href="/images/favicons/android-chrome-192x192.png">
        <link rel="android-chrome-icon" type="image/png" sizes="512x512" href="/images/favicons/android-chrome-512x512.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
        <link rel="icon" href="/images/favicons/favicon.ico">
        <link rel="manifest" href="/images/favicons/site.webmanifest">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
<script type="text/javascript">
    @auth
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: {!! auth()->check()?auth()->user()->jsPermissions():null !!}
        }
    @else
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: [],
        }
    @endauth
</script>
