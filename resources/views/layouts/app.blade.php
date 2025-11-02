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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1yH6fWlK6e6/o73vB3fQ6R3z5Q5aG6X/oB1t7O6u5P4v8D5d7E5t8e5t8t8e5t8g6x9/u4aK7n3M7Q7w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">

        {{-- 2. Carga los assets principales de Laravel/Vite --}}
        @vite([
        'resources/css/app.css', 
        'resources/js/app.js'
        ])
        
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen"> 
            
            @include('layouts.sidebar')

            <div class="ml-64">
                
                {{-- Contenido del encabezado --}}
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
    
                {{-- Contenido principal de la vista --}}
                <main class="py-4"> 
                    
                    {{-- Bloque para mensajes de éxito --}}
                    @if (session('success'))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Bloque para mensajes de error --}}
                    @if (session('error'))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- 👇 SOLUCIÓN CLAVE: Si se usa <x-app-layout> (Periodos Nomina), usa $slot. 
                         Si se usa @extends (Configuracion), usa @yield('content'). --}}
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </main>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>