@extends('layouts.app')

@section('content')
<div class="py-12"> 
    {{-- APLICAR MX-AUTO y MAX-W-7XL para CENTRAR el contenido --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"> 
            
            <div class="flex justify-between items-center mb-6 border-b pb-2">
                <h2 class="text-2xl font-semibold leading-tight text-indigo-700">Gestión de Cargos y Salarios</h2>
                <a href="{{ route('job_titles.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md">
                    + Añadir Nuevo Cargo
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- 2. CORRECCIÓN DE LA ESTRUCTURA DE LA TABLA --}}
            <div class="overflow-x-auto">
                {{-- Usamos una estructura de tabla limpia con ancho completo --}}
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NOMBRE DEL CARGO
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SALARIO BASE SUGERIDO
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ACCIONES
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @foreach($jobTitles as $jobTitle)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $jobTitle->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($jobTitle->salario_base, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('job_titles.edit', $jobTitle) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Editar
                                </a>
                                {{-- 2. Formulario para Eliminación--}}
                                <form action="{{ route('job_titles.destroy', $jobTitle) }}" method="POST" 
                                    onsubmit="return confirm('¿Está seguro de que desea eliminar el cargo {{ $jobTitle->nombre }}?');" 
                                    class="inline-block ml-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection