@extends('layouts.app')

@section('content')
<div class="ml-64 py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold leading-tight text-indigo-700 border-b pb-2 mb-6">
                Editar Cargo: {{ $jobTitle->nombre }}
            </h2>

            {{-- El formulario apunta a la ruta 'update' y utiliza el método PATCH --}}
            <form method="POST" action="{{ route('job_titles.update', $jobTitle) }}">
                @csrf
                @method('PATCH')

                {{-- Campo Nombre del Cargo --}}
                <div class="mb-4">
                    <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre del Cargo</label>
                    <input type="text" id="nombre" name="nombre" required 
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                           {{-- El valor debe ser el antiguo si hay error, o el valor actual del objeto --}}
                           value="{{ old('nombre', $jobTitle->nombre) }}" placeholder="Ej: Ingeniero de Sistemas">
                    @error('nombre')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Campo Salario Base Sugerido --}}
                <div class="mb-6">
                    <label for="salario_base" class="block font-medium text-sm text-gray-700">Salario Base Sugerido ($)</label>
                    <input type="number" id="salario_base" name="salario_base" required 
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                           {{-- El valor debe ser el antiguo si hay error, o el valor actual del objeto --}}
                           value="{{ old('salario_base', $jobTitle->salario_base) }}" min="0" step="1000" placeholder="Ej: 2500000">
                    <p class="text-xs text-gray-500 mt-1">Este es un valor de referencia para el cargo.</p>
                    @error('salario_base')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-8">
                    <a href="{{ route('job_titles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                        Actualizar Cargo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection