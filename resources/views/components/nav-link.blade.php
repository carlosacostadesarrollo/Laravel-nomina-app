@props(['href', 'active' => false])

@php
// Clases base para el diseño limpio y moderno de la barra lateral
$baseClasses = 'flex items-center p-3 rounded-lg transition duration-150 ease-in-out w-full';

// Estilos para el estado Activo/Inactivo (Vanguardista)
$classes = ($active ?? false)
            ? 'bg-indigo-100 text-indigo-700 font-semibold' // Estilo activo: fondo claro y texto de acento
            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'; // Estilo inactivo: texto gris, hover suave
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $classes]) }}>
    {{ $slot }}
</a>
