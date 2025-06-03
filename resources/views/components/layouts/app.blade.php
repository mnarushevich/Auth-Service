<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>

    <meta name="application-name" content="{{ config('app.name') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased">

<div class="flex h-screen bg-gray-100">

    @livewire('layouts.sidebar')

    <!-- Main content -->
    <div class="flex flex-col flex-1 overflow-y-auto">
        @livewire('layouts.navbar')
        <div class="p-4">
            {{ $slot }}
        </div>
    </div>

</div>


@livewire('notifications')

@filamentScripts
@vite('resources/js/app.js')
</body>
</html>
<?php 
