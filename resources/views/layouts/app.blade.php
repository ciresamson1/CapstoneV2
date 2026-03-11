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
    </head>
    <body class="font-sans antialiased bg-gray-100">

<div class="flex min-h-screen">

<!-- Sidebar -->
<div class="w-64 bg-gray-900 text-white">

<div class="p-6 text-xl font-bold border-b border-gray-700">
Project Manager
</div>

<nav class="mt-6">

<a href="{{ route('dashboard') }}" class="block py-3 px-6 hover:bg-gray-700">
Dashboard
</a>

<a href="{{ route('projects.index') }}" class="block py-3 px-6 hover:bg-gray-700">
Projects
</a>

<a href="{{ route('tasks.index') }}" class="block py-3 px-6 hover:bg-gray-700">
Tasks
</a>

<a href="{{ route('tasks.gantt') }}" class="block py-3 px-6 hover:bg-gray-700">
Gantt Chart
</a>

<a href="{{ route('users.index') }}" class="block py-3 px-6 hover:bg-gray-700">
Users
</a>

<a href="{{ route('roles.index') }}" class="block py-3 px-6 hover:bg-gray-700">
Roles
</a>

</nav>

</div>

<!-- Main Content -->
<div class="flex-1">

@include('layouts.navigation')

<!-- Page Heading -->
@if (isset($header))
<header class="bg-white shadow">
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
{{ $header }}
</div>
</header>
@endif

<!-- Page Content -->
<main>
{{ $slot }}
</main>

</div>

</div>

</body>
</html>
