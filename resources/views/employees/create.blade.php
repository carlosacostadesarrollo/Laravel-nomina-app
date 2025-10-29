@extends('layouts.app')

@section('content')
{{-- CORRECCIÓN DE DISEÑO: Quitamos 'ml-64' y centramos el contenido, ajustando el ancho a 'max-w-4xl' --}}
<div class="py-12"> 
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> 
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"> 
            <h2 class="text-2xl font-semibold leading-tight mb-6 text-indigo-700 border-b pb-2">Añadir Nuevo Empleado</h2>

            <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Mensajes de error de validación --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 border border-red-400 bg-red-100 text-red-700 rounded">
                        <p class="font-bold">¡Atención! Hay errores de validación:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Contenedor de la Grilla --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nombre --}}
                    <div>
                        <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('nombre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Apellido --}}
                    <div>
                        <label for="apellido" class="block font-medium text-sm text-gray-700">Apellido *</label>
                        <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('apellido')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    {{-- Identificación --}}
                    <div>
                        <label for="identificacion" class="block font-medium text-sm text-gray-700">Identificación (Cédula/ID) *</label>
                        <input type="text" id="identificacion" name="identificacion" value="{{ old('identificacion') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('identificacion')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fecha de Nacimiento --}}
                    <div>
                        <label for="fecha_nacimiento" class="block font-medium text-sm text-gray-700">Fecha de Nacimiento *</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('fecha_nacimiento')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Sexo --}}
                    <div>
                        <label for="sexo" class="block font-medium text-sm text-gray-700">Sexo *</label>
                        <select id="sexo" name="sexo" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">Seleccione</option>
                            <option value="F" @selected(old('sexo') == 'F')>Femenino</option>
                            <option value="M" @selected(old('sexo') == 'M')>Masculino</option>
                            <option value="O" @selected(old('sexo') == 'O')>Otro</option>
                        </select>
                        @error('sexo')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    {{-- Teléfono --}}
                    <div>
                        <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('telefono')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-700">Email de Contacto</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    {{-- CARGO / PUESTO (SELECT - Usa los datos del controlador) --}}
                    <div>
                        <label for="job_title_id" class="block font-medium text-sm text-gray-700">Cargo / Puesto *</label>
                        <select id="job_title_id" name="job_title_id" required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">Seleccione un Cargo</option>
                            
                            @if(isset($jobTitles))
                                @foreach($jobTitles as $jobTitle)
                                    <option value="{{ $jobTitle->id }}" 
                                            @selected(old('job_title_id') == $jobTitle->id)
                                            {{-- CLAVE: Aquí se pasa el salario como data-attribute --}}
                                            data-salario="{{ $jobTitle->salario_base }}">
                                        {{ $jobTitle->nombre }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('job_title_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Salario Base --}}
                    <div>
                        <label for="salario_base" class="block font-medium text-sm text-gray-700">Salario Base *</label>
                        <input type="number" step="0.01" id="salario_base" name="salario_base" value="{{ old('salario_base') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('salario_base')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fecha de Ingreso --}}
                    <div>
                        <label for="fecha_ingreso" class="block font-medium text-sm text-gray-700">Fecha de Ingreso *</label>
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('fecha_ingreso')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    {{-- Dirección (ocupa ambas columnas) --}}
                    <div class="md:col-span-2"> 
                        <label for="direccion" class="block font-medium text-sm text-gray-700">Dirección Residencial</label>
                        <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        @error('direccion')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
                
                {{-- Campo de Foto --}}
                <div class="mt-6"> 
                    <label for="foto" class="block font-medium text-sm text-gray-700">Foto del Empleado (Opcional)</label>
                    <input type="file" id="foto" name="foto" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    @error('foto')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                        Guardar Empleado
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection {{-- CIERRE DE @section('content') --}}


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jobTitleSelect = document.getElementById('job_title_id');
        const salarioBaseInput = document.getElementById('salario_base');

        // Función que actualiza el salario
        function updateSalarioBase() {
            const selectedOption = jobTitleSelect.options[jobTitleSelect.selectedIndex];
            const salario = selectedOption.getAttribute('data-salario');

            if (salario) {
                // Actualiza el campo de salario base
                salarioBaseInput.value = parseFloat(salario).toFixed(2);
            } else {
                salarioBaseInput.value = '';
            }
        }

        // 1. Ejecutar la función cuando el valor del select cambie
        jobTitleSelect.addEventListener('change', updateSalarioBase);

        // 2. Ejecutar al cargar la página si ya hay un valor seleccionado (por old())
        if (jobTitleSelect.value) {
            updateSalarioBase();
        }
    });
</script>
@endpush