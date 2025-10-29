@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900">
        Detalles del Contrato N° {{ $contract->numero_contrato ?? $contract->id }}
    </h1>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        
        {{-- Sección de Datos del Empleado --}}
        <div class="px-4 py-5 sm:px-6 bg-indigo-50">
            <h3 class="text-lg leading-6 font-medium text-indigo-600">
                Información del Empleado
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                {{-- ID y Cargo --}}
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Número de Contrato:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->numero_contrato }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Empleado:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $contract->employee->nombre ?? 'N/A' }} {{ $contract->employee->apellido ?? '' }} ({{ $contract->employee->identificacion ?? 'N/A' }})
                    </dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Cargo:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->jobTitle->nombre ?? 'N/A' }}</dd>
                </div>
                
                {{-- Fechas y Tipo --}}
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Fecha de Inicio:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($contract->fecha_inicio)->format('d/m/Y') }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Fecha Final:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $contract->fecha_fin ? \Carbon\Carbon::parse($contract->fecha_fin)->format('d/m/Y') : 'Indefinido/N/A' }}
                    </dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Tipo de Contrato:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->contractType->nombre ?? 'N/A' }}</dd>
                </div>
                
                {{-- Fondos y Entidades --}}
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">EPS:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->eps->nombre ?? 'N/A' }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">ARL:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->arlEntity->nombre ?? 'N/A' }} (Nivel {{ $contract->riskLevel->nombre ?? 'N/A' }})</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Pensión:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->pensionFund->nombre ?? 'N/A' }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Cesantías:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $contract->cesantiasFund->nombre ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <a href="{{ route('contracts.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Volver a la Lista
            </a>
            <a href="{{ route('contracts.edit', $contract) }}" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Editar Contrato
            </a>
        </div>
    </div>
</div>
@endsection