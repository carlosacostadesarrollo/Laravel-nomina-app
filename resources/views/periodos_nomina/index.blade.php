<x-app-layout>
    
    {{-- Encabezado de la Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Períodos de Nómina') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                    {{-- CORREGIDO: Se cambia fa-solid a fa (aunque el ícono original era fa-trash-can) --}}
                    <i class="fa fa-trash-can mr-2"></i> {{ session('error') }}
                </div>
            @endif
            
            {{-- Botón para Crear Nuevo Período --}}
            <div class="mb-6 flex justify-end">
                <a href="{{ route('periodos_nomina.create') }}" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    {{-- CORREGIDO: Se cambia fa-solid a fa --}}
                    <i class="fa fa-plus mr-2"></i> Crear Nuevo Período
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <h3 class="text-lg font-bold mb-4">Listado de Períodos de Pago</h3>
                    
                    {{-- Tabla de Períodos --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Año / Mes</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración (días)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($periodos as $periodo)
                                    @php
                                        // Lógica de Carbon
                                        $inicio = \Carbon\Carbon::parse($periodo->fecha_inicio);
                                        $fin = \Carbon\Carbon::parse($periodo->fecha_fin);
                                        $duracionDias = $inicio->diffInDays($fin) + 1;
                                        
                                        // Mapeo simple de meses
                                        $meses = ['01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'];
                                        $nombreMes = $meses[$periodo->mes] ?? $periodo->mes; 
                                        
                                        // Mapeo de colores de estado
                                        $color = [
                                            'Abierto' => 'bg-green-100 text-green-800', 
                                            'Cerrado' => 'bg-yellow-100 text-yellow-800', 
                                            'Pagado' => 'bg-blue-100 text-blue-800'
                                        ][$periodo->estado] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $periodo->anio }} / {{ $nombreMes }} 
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $inicio->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $fin->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $duracionDias }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ $periodo->estado }}
                                            </span>
                                        </td>
                                        
                                        {{-- BLOQUE DE ACCIONES CORREGIDO Y MEJORADO --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            
                                            {{-- 1. Botón de Editar (CLASE CORREGIDA) --}}
                                            <a href="{{ route('periodos_nomina.edit', $periodo->id) }}" 
                                                title="Editar Período"
                                                class="text-blue-600 hover:text-blue-900 inline-flex items-center mr-3 transition duration-150">
                                                <i class="fa fa-edit text-lg"></i>
                                            </a>
                                            
                                            {{-- 2. Botón de Eliminar (CLASE CORREGIDA) --}}
                                            <form action="{{ route('periodos_nomina.destroy', $periodo->id) }}" method="POST" class="inline" 
                                                onsubmit="return confirm('⚠️ ATENCIÓN: ¿Está seguro de que desea eliminar permanentemente el período {{ $periodo->anio }}/{{ $nombreMes }}? Esta acción es irreversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    title="Eliminar Período"
                                                    class="text-red-600 hover:text-red-900 transition duration-150">
                                                    <i class="fa fa-trash-alt text-lg"></i>
                                                </button>
                                            </form>
                                            
                                        </td>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Colspan ajustado a 6 --}}
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No hay períodos de nómina registrados. ¡Cree uno nuevo!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</x-app-layout>