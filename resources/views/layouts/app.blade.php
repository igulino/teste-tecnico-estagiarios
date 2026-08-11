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

            [x-cloak] {
                display: none !important;
            }

            .app-shell {
                background-color: var(--layout-bg);
            }

            .app-header {
                background-color: var(--layout-top);
                border-bottom: 1px solid var(--layout-line);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
            }

            .app-header h2 {
                color: #D8DDDD;
            }

            .app-header a {
                background-color: #3F4241;
                border-color: #666B6A;
                color: #E5E7EB;
            }

            .app-header a:hover {
                background-color: #505453;
                color: #FFFFFF;
            }

            .dashboard-page {
                min-height: 100vh;
                background-color: var(--layout-bg);
            }

            .dashboard-card {
                background-color: #eef1f3;
                box-shadow: 0 16px 35px rgba(0, 0, 0, 0.16);
            }

            .dashboard-table-head {
                background-color: var(--dashboard-table-head);
            }

            .dashboard-table-body {
                background-color: var(--dashboard-table-body);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="app-shell min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="app-header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
