@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-4">Lista de Contratos</h1>
    
    {{-- Botón Crear Nuevo Contrato --}}
    <a href="{{ route('contracts.create') }}" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mb-4">
        Crear Nuevo Contrato
    </a>
    
    {{-- Mensajes Flash (Success o Error) --}}
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        {{-- Muestra el mensaje de error de la función destroy (Dependencias) --}}
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {!! session('error') !!} {{-- Usamos {!! !!} si el mensaje de error incluye HTML (como <strong>) --}}
        </div>
    @endif

    {{-- Tabla de Contratos --}}
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
                @forelse($contracts as $contract)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->employee->nombre ?? 'N/A' }} {{ $contract->employee->apellido ?? '' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->jobTitle->nombre ?? 'N/A' }}</td>
                    
                    {{-- Formato de Salario Consistente --}}
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($contract->employee->salario_base ?? 0, 0, ',', '.') }}</td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($contract->fecha_inicio)->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->contractType->nombre ?? 'N/A' }}</td>
                    
                    {{-- Manejo de Nulos (usando relaciones que asumimos existen en el modelo Contract) --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->eps->nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->arlEntity->nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->pensionFund->nombre ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contract->cesantiasFund->nombre ?? 'N/A' }}</td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        {{-- Botón Ver --}}
                        <a href="{{ route('contracts.show', $contract) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Ver</a>
                        
                        {{-- Botón Editar --}}
                        <a href="{{ route('contracts.edit', $contract) }}" class="text-blue-600 hover:text-blue-900 mr-2">Editar</a>
                        
                        {{-- FORMULARIO DE ELIMINACIÓN (DELETE) --}}
                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="inline-block" 
                              onsubmit="return confirm('¿Confirmas que deseas eliminar el contrato N° {{ $contract->numero_contrato }}? \n\n¡Advertencia! Solo se podrá eliminar si no tiene pagos asociados.');">
                            
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-1">
                                Eliminar
                            </button>
                        </form>
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