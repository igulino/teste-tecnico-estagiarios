<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --layout-bg: #3A3D3C;
                --layout-top: #2F3231;
                --layout-line: #4A4D4C;
                --dashboard-card: #B9C0C3;
                --dashboard-table-head: #B9C0C3;
                --dashboard-table-body: #D5D9DA;
            }
            .login-page{
                min-height: 100vh;
                background-color: #2F3231;
            }
        </style>
    </head>
    <body class="login-page font-sans text-gray-900 antialiased">
        <div class="login-page flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden ">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
