@extends('layouts.app') 

@section('content')
<div class="container">
    <h1 class="text-3xl font-bold mb-4">Lista de Contratos</h1>
    
    {{-- Botón con clases de diseño coherentes --}}
    <a href="{{ route('contracts.create') }}" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mb-4">
        Crear Nuevo Contrato
    </a>
    
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Estilos de tabla simples para mayor consistencia visual --}}
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F. Inicio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Contrato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">EPS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ARL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pensión</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cesantías</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Asegúrate de pasar la variable $contracts desde el controlador --}}
                @forelse($contracts as $contract)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->id }}</td>
                    {{-- Muestra el nombre completo usando la relación employee --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->employee->nombre ?? 'N/A' }} {{ $contract->employee->apellido ?? '' }}</td>
                    {{-- Muestra el cargo usando la relación jobTitle --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->jobTitle->nombre ?? 'N/A' }}</td>
                    
                    {{-- CORRECCIÓN DE SALARIO: Debe obtenerse del empleado o del contrato (si tiene campo salario) --}}
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($contract->employee->salario_base ?? 0, 0, ',', '.') }}</td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($contract->fecha_inicio)->format('Y-m-d') }}</td>
                    {{-- Muestra el tipo de contrato --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->contractType->nombre ?? 'N/A' }}</td>
                    
                    {{-- Manejo de Nulos con el operador '??' --}}
                    
                    {{-- EPS: Si la relación es NULL, muestra 'N/A' --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->eps->nombre ?? 'N/A' }}</td>
                    
                    {{-- CORRECCIÓN DE ARL: Usamos la relación arlEntity (basado en arl_entity_id) --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->arlEntity->nombre ?? 'N/A' }}</td>
                    
                    {{-- PENSIÓN: Si la relación es NULL, muestra "NO APLICA" --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->pensionFund->nombre ?? 'NO PAGA PENSIÓN' }}</td>
                    
                    {{-- CESANTÍAS: Si la relación es NULL, muestra "NO APLICA" --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->cesantiasFund->nombre ?? 'NO APLICA' }}</td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        {{-- Botón "Ver" actualizado para usar la ruta de Laravel --}}
                        <a href="{{ route('contracts.show', $contract->id) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No hay contratos registrados aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection