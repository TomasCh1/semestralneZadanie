{{-- resources/views/layouts/admin.blade.php --}}

        <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin • {{ config('app.name') }}</title>

    {{-- Vite načte Tailwind CSS a JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific styles --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">

{{-- Hlavná navigácia --}}
@include('layouts.navigation')

{{-- Obsah stránky --}}
<main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    @yield('content')
</main>

{{-- Page-specific scripts --}}
@stack('scripts')
</body>
</html>
