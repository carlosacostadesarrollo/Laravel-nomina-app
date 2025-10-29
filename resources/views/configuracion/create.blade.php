@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold leading-tight mb-6 text-indigo-700 border-b pb-2">
                    Configuración General de la Empresa
                </h2>
                <p class="mb-6 text-sm text-gray-600">
                    Esta configuración se utilizará para todos los cálculos de nómina.
                </p>
                
                <form method="POST" action="{{ route('configuracion.store') }}">
                    @csrf
                    {{-- Usaremos el método PUT si se encuentra una configuración existente para actualizar --}}
                    @if (isset($configuracion) && $configuracion->exists)
                        @method('PUT') 
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                            <p class="font-bold">Por favor, corrija los siguientes errores:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-6 mb-8">
                        <h3 class="text-lg font-bold mb-3 text-gray-700 border-b pb-2">Datos de Identificación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nombre/Razón Social --}}
                            <div>
                                <label for="nombre_empresa" class="block font-medium text-sm text-gray-700">Nombre o Razón Social</label>
                                <input type="text" id="nombre_empresa" name="nombre_empresa" value="{{ old('nombre_empresa', $configuracion->nombre_empresa ?? '') }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>

                            {{-- NIT / RIF --}}
                            <div>
                                <label for="nit" class="block font-medium text-sm text-gray-700">NIT / RIF</label>
                                <input type="text" id="nit" name="nit" value="{{ old('nit', $configuracion->nit ?? '') }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>

                            {{-- Dirección --}}
                            <div class="md:col-span-2">
                                <label for="direccion" class="block font-medium text-sm text-gray-700">Dirección Fiscal</label>
                                <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $configuracion->direccion ?? '') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            
                            {{-- Teléfono y Email --}}
                            <div>
                                <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $configuracion->telefono ?? '') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            <div>
                                <label for="email_contacto" class="block font-medium text-sm text-gray-700">Email de Contacto</label>
                                <input type="email" id="email_contacto" name="email_contacto" value="{{ old('email_contacto', $configuracion->email_contacto ?? '') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                            
                            {{-- Moneda Base --}}
                            <div>
                                <label for="moneda_base" class="block font-medium text-sm text-gray-700">Moneda Base (Ej: COP, USD)</label>
                                <input type="text" id="moneda_base" name="moneda_base" value="{{ old('moneda_base', $configuracion->moneda_base ?? 'COP') }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-4 border-t">
                        <h3 class="text-lg font-bold mb-3 text-gray-700 border-b pb-2">Parámetros de Nómina y Legales</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Salario Mínimo Legal --}}
                            <div class="md:col-span-2">
                                <label for="salario_minimo_legal" class="block font-medium text-sm text-gray-700">Salario Mínimo Mensual Legal Vigente (SMMLV)</label>
                                <input type="number" step="0.01" id="salario_minimo_legal" name="salario_minimo_legal" value="{{ old('salario_minimo_legal', $configuracion->salario_minimo_legal ?? '') }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>

                            {{-- Período de Nómina y Fecha de Inicio --}}
                            <div>
                                <label for="dias_periodo_nomina" class="block font-medium text-sm text-gray-700">Días de Período de Nómina</label>
                                <select id="dias_periodo_nomina" name="dias_periodo_nomina" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="15" @if(old('dias_periodo_nomina', $configuracion->dias_periodo_nomina ?? '') == 15) selected @endif>Quincenal (15 Días)</option>
                                    <option value="30" @if(old('dias_periodo_nomina', $configuracion->dias_periodo_nomina ?? '') == 30) selected @endif>Mensual (30 Días)</option>
                                    <option value="7" @if(old('dias_periodo_nomina', $configuracion->dias_periodo_nomina ?? '') == 7) selected @endif>Semanal (7 Días)</option>
                                </select>
                            </div>

                            <div>
                                <label for="fecha_inicio_periodo" class="block font-medium text-sm text-gray-700">Fecha de Inicio del Período Fiscal</label>
                                <input type="date" id="fecha_inicio_periodo" name="fecha_inicio_periodo" value="{{ old('fecha_inicio_periodo', $configuracion->fecha_inicio_periodo ?? '') }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            </div>
                        </div>

                        <div class="pt-4 border-t mt-6">
                            <h3 class="text-lg font-bold mb-3 text-gray-700">Aportes a Cargo del Empleador (%)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="porcentaje_salud_empresa" class="block font-medium text-sm text-gray-700">Salud (Empresa)</label>
                                    <input type="number" step="0.01" id="porcentaje_salud_empresa" name="porcentaje_salud_empresa" value="{{ old('porcentaje_salud_empresa', $configuracion->porcentaje_salud_empresa ?? 8.5) }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                </div>
                                <div>
                                    <label for="porcentaje_pension_empresa" class="block font-medium text-sm text-gray-700">Pensión (Empresa)</label>
                                    <input type="number" step="0.01" id="porcentaje_pension_empresa" name="porcentaje_pension_empresa" value="{{ old('porcentaje_pension_empresa', $configuracion->porcentaje_pension_empresa ?? 12.0) }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                </div>
                                <div>
                                    <label for="porcentaje_arl" class="block font-medium text-sm text-gray-700">ARL (Riesgos)</label>
                                    <input type="number" step="0.01" id="porcentaje_arl" name="porcentaje_arl" value="{{ old('porcentaje_arl', $configuracion->porcentaje_arl ?? '') }}" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t mt-6">
                            <h3 class="text-lg font-bold mb-3 text-gray-700">Aportes a Cargo del Empleado (%) - Referencia</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Aporte Salud (Empleado)</label>
                                    <input type="number" value="4.00" disabled class="border-gray-300 bg-gray-100 rounded-md shadow-sm mt-1 block w-full cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500">Valor estándar (4%). No se guarda en esta tabla.</p>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Aporte Pensión (Empleado)</label>
                                    <input type="number" value="4.00" disabled class="border-gray-300 bg-gray-100 rounded-md shadow-sm mt-1 block w-full cursor-not-allowed">
                                    <p class="mt-1 text-xs text-gray-500">Valor estándar (4%). No se guarda en esta tabla.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                            Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection