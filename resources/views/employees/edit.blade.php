@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold leading-tight mb-6">Editar Empleado: {{ $employee->nombre }} {{ $employee->apellido }}</h2>

                {{-- FORMULARIO DE EDICIÓN --}}
                <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH') {{-- Usa el método PATCH para actualizar recursos --}}

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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $employee->nombre) }}" required autofocus
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>

                        {{-- Apellido --}}
                        <div>
                            <label for="apellido" class="block font-medium text-sm text-gray-700">Apellido</label>
                            <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $employee->apellido) }}" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>
                        
                        {{-- Identificación --}}
                        <div>
                            <label for="identificacion" class="block font-medium text-sm text-gray-700">Identificación (Cédula/ID)</label>
                            <input type="text" id="identificacion" name="identificacion" value="{{ old('identificacion', $employee->identificacion) }}" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>

                        {{-- Fecha de Nacimiento --}}
                        <div>
                            <label for="fecha_nacimiento" class="block font-medium text-sm text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $employee->fecha_nacimiento?->format('Y-m-d')) }}"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>

                        {{-- Sexo --}}
                        <div>
                            <label for="sexo" class="block font-medium text-sm text-gray-700">Sexo</label>
                            <select id="sexo" name="sexo" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                @php $selectedSex = old('sexo', $employee->sexo); @endphp
                                <option value="">Seleccione</option>
                                <option value="F" @selected($selectedSex == 'F')>Femenino</option>
                                <option value="M" @selected($selectedSex == 'M')>Masculino</option>
                                <option value="O" @selected($selectedSex == 'O')>Otro</option>
                            </select>
                        </div>
                        
                        {{-- Teléfono --}}
                        <div>
                            <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $employee->telefono) }}"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">Email de Contacto</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>
                        
                        {{-- Cargo --}}
                           <div>
                            <label for="job_title_id" class="block font-medium text-sm text-gray-700">Cargo / Puesto *</label>
                            <select id="job_title_id" name="job_title_id" required 
                             class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">Seleccione un Cargo</option>
                
                            @if(isset($jobTitles))
                            @foreach($jobTitles as $jobTitle)
                            <option value="{{ $jobTitle->id }}" 
                            {{-- LÓGICA CLAVE: Selecciona el cargo si coincide con el ID guardado en el objeto $employee --}}
                            @selected(old('job_title_id', $employee->job_title_id) == $jobTitle->id) 
                    
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
                            <label for="salario_base" class="block font-medium text-sm text-gray-700">Salario Base</label>
                            <input type="number" step="0.01" id="salario_base" name="salario_base" value="{{ old('salario_base', $employee->salario_base) }}" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>

                        {{-- Fecha de Ingreso --}}
                        <div>
                            <label for="fecha_ingreso" class="block font-medium text-sm text-gray-700">Fecha de Ingreso</label>
                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso', $employee->fecha_ingreso?->format('Y-m-d')) }}" required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>
                        
                        {{-- Dirección --}}
                        <div class="md:col-span-2"> 
                            <label for="direccion" class="block font-medium text-sm text-gray-700">Dirección Residencial</label>
                            <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $employee->direccion) }}"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        </div>

                    </div>

                    {{-- Campo de Foto Actual --}}
                    <div class="mt-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Foto Actual</label>
                        @if ($employee->foto_path)
                            <img src="{{ asset('storage/' . $employee->foto_path) }}" alt="Foto actual" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <p class="text-sm text-gray-500">No hay foto cargada.</p>
                        @endif
                    </div>
                    
                    {{-- Campo de Subir Nueva Foto --}}
                    <div class="mt-4 md:col-span-2"> 
                        <label for="foto" class="block font-medium text-sm text-gray-700">Subir Nueva Foto (dejar vacío para mantener la actual)</label>
                        <input type="file" id="foto" name="foto" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    </div>
                    
                    <div class="flex items-center justify-end mt-6 md:col-span-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                            Actualizar Empleado
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection