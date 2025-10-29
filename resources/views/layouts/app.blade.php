<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- 1. Dependencias de fuentes y iconos (Font Awesome) --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        {{-- Font Awesome (Iconos) --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJd/rB1v3w38v3v0T5oP5v5J5yB2L6pXjG0r5o3+j6aH3v4B4x1a5i4t6/6pA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        {{-- 2. Carga los assets principales de Laravel/Vite --}}
        @vite([
        'resources/css/app.css', 
        
        'resources/js/app.js'
        ])
        
    </head>
    <body class="font-sans antialiased">
        {{-- MODIFICACIÓN CRÍTICA: Se eliminó la clase 'bg-gray-100' para permitir que el fondo se herede del CSS. --}}
        <div class="min-h-screen"> 
            
            @include('layouts.sidebar')

            <div class="ml-64">
                
                {{-- Contenido del encabezado (opcional en Laravel Breeze/Jetstream) --}}
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
    
                {{-- Contenido principal de la vista (donde se inyecta @yield('content')) --}}
                <main class="py-4"> 
                    @yield('content')
                </main>
            </div>
            
        </div>
        
        {{-- Carga de scripts específicos de la vista (usando @push) --}}
        @stack('scripts')
    </body>
</html>