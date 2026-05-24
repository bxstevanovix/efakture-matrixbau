<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>E-faktura | Matrixbau</title>

        <link rel="icon" href="{{ asset('f-circle.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @php
            $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
        @endphp

        @if ($hasViteAssets)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                * { box-sizing: border-box; }
                html, body { margin: 0; min-height: 100%; }
                body {
                    background: #f3f4f6;
                    color: #111827;
                    font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                }
                .guest-page {
                    align-items: center;
                    display: flex;
                    flex-direction: column;
                    min-height: 100vh;
                    padding: 1.5rem 1rem;
                }
                .guest-logo svg {
                    color: #6b7280;
                    height: 5rem;
                    width: 5rem;
                }
                .guest-card {
                    background: #ffffff;
                    border-radius: .5rem;
                    box-shadow: 0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
                    margin-top: 1.5rem;
                    max-width: 28rem;
                    overflow: hidden;
                    padding: 1.5rem;
                    width: 100%;
                }
                label { color: #374151; display: block; font-size: .875rem; font-weight: 500; }
                input[type="email"],
                input[type="password"],
                input[type="text"] {
                    border: 1px solid #d1d5db;
                    border-radius: .375rem;
                    box-shadow: 0 1px 2px 0 rgb(0 0 0 / .05);
                    display: block;
                    font: inherit;
                    margin-top: .25rem;
                    padding: .5rem .75rem;
                    width: 100%;
                }
                input:focus {
                    border-color: rgba(91, 70, 178, 0.62);
                    box-shadow: 0 0 0 4px rgba(91, 70, 178, 0.12);
                    outline: 0;
                }
                button,
                a[href] {
                    border-radius: .375rem;
                    font-size: .875rem;
                }
                button {
                    background: #1f2937;
                    border: 0;
                    color: #ffffff;
                    cursor: pointer;
                    font-weight: 600;
                    padding: .6rem 1rem;
                    text-transform: uppercase;
                }
                a[href] { color: #4b5563; }
                .mb-4 { margin-bottom: 1rem; }
                .mt-2 { margin-top: .5rem; }
                .mt-4 { margin-top: 1rem; }
                .flex { display: flex; }
                .items-center { align-items: center; }
                .justify-end { justify-content: flex-end; }
                .text-sm { font-size: .875rem; }
                .text-gray-600 { color: #4b5563; }
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="guest-page min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div class="guest-logo">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="guest-card w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
