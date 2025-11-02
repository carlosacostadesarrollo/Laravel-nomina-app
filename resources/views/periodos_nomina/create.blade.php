<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Período de Nómina') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Mostrar errores de validación --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            <p class="font-bold">¡Atención! Hay errores de validación:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- TÍTULO DE LA SECCIÓN (Según tu imagen) --}}
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">:: CONFIGURACIÓN DE MES VIGENTE</h3>

                    <form method="POST" action="{{ route('periodos_nomina.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Campo Año --}}
                            <div>
                                <label for="anio" class="block font-medium text-sm text-gray-700">Año *</label>
                                <select id="anio" name="anio" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione...</option>
                                    @for ($i = date('Y'); $i <= date('Y') + 3; $i++)
                                        <option value="{{ $i }}" {{ old('anio') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('anio')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Mes --}}
                            <div>
                                <label for="mes" class="block font-medium text-sm text-gray-700">Mes *</label>
                                <select id="mes" name="mes" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione...</option>
                                    @php
                                        $meses = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];
                                    @endphp
                                    @foreach ($meses as $num => $nombre)
                                        <option value="{{ $num }}" {{ old('mes') == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                                @error('mes')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha de Inicio --}}
                            <div>
                                <label for="fecha_inicio" class="block font-medium text-sm text-gray-700">Fecha Inicio *</label>
                                <input id="fecha_inicio" type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('fecha_inicio')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha de Fin --}}
                            <div>
                                <label for="fecha_fin" class="block font-medium text-sm text-gray-700">Fecha Fin *</label>
                                <input id="fecha_fin" type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('fecha_fin')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado del Período --}}
                            <div>
                                <label for="estado" class="block font-medium text-sm text-gray-700">Estado *</label>
                                <select id="estado" name="estado" required 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione...</option>
                                    <option value="Abierto" {{ old('estado') == 'Abierto' ? 'selected' : '' }}>Abierto</option>
                                    <option value="Cerrado" {{ old('estado') == 'Cerrado' ? 'selected' : '' }}>Cerrado</option>
                                    <option value="Pagado" {{ old('estado') == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                                </select>
                                @error('estado')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Espacio para que el estado no se vea cortado --}}
                            <div class="hidden md:block"></div> 

                        </div>

                        {{-- Botones de Acción (Simulando los iconos que tenías) --}}
                        <div class="flex items-center justify-end mt-6 space-x-3">
                             <button type="submit" class="inline-flex items-center justify-center p-3 bg-red-600 border border-transparent rounded-full text-white hover:bg-red-700 transition duration-150">
                                <i class="fa-solid fa-save"></i>
                            </button>
                             <button type="button" class="inline-flex items-center justify-center p-3 bg-sky-500 border border-transparent rounded-full text-white hover:bg-sky-600 transition duration-150">
                                <i class="fa-solid fa-search"></i>
                            </button>
                             <button type="button" class="inline-flex items-center justify-center p-3 bg-gray-200 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-300 transition duration-150">
                                <i class="fa-solid fa-tree"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>