<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kana Covers') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cinzel:400,700|lato:400,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-stone-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white border border-stone-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden rounded-none transition-all duration-300">
                {{ $slot }}
            </div>
        </div>

        <style>
            .font-serif { font-family: 'Cinzel', serif; }
            .font-sans { font-family: 'Lato', sans-serif; }
        </style>
    </body>
</html>