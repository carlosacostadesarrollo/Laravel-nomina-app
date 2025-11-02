@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mt-5 md:mt-0 md:col-span-2">
        
        {{-- Título de la Vista --}}
        <h1 class="text-3xl font-bold mb-6 text-gray-900"> 
            Editar Contrato N° {{ $contract->numero_contrato ?? $contract->id }}
        </h1>

        {{-- Formulario de Actualización --}}
        <form method="POST" action="{{ route('contracts.update', $contract) }}" id="contractEditForm">
            @csrf
            {{-- CRUCIAL: Usar PUT/PATCH para actualizar recursos RESTful --}}
            @method('PUT')

            {{-- Manejo de Errores de Validación --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error: Falló la validación:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">

                    {{-- ===================================================================
                        1. DATOS DE IDENTIFICACIÓN, CARGO Y SALARIO (Solo Lectura)
                        =================================================================== --}}
                    <h2 class="text-xl font-semibold mb-4 text-indigo-600">
                        Datos de Identificación, Cargo y Salario
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Número de Identificación (Solo lectura) --}}
                        <div class="col-span-3 sm:col-span-1">
                            <label for="identificacion" class="block text-sm font-medium text-gray-700">Número de Identificación *:</label>
                            <input type="text" value="{{ $contract->employee->identificacion ?? 'N/A' }}" disabled 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                        </div>
                        
                        {{-- Nombres Completos (Solo lectura) --}}
                        <div class="col-span-3 sm:col-span-1">
                            <label for="nombres_completos" class="block text-sm font-medium text-gray-700">Nombres Completos:</label>
                            <input type="text" value="{{ ($contract->employee->nombre ?? 'N/A') . ' ' . ($contract->employee->apellido ?? '') }}" disabled 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                        </div>

                        {{-- Salario Base (Solo lectura) --}}
                        <div class="col-span-3 sm:col-span-1">
                            <label for="salario_display" class="block text-sm font-medium text-gray-700">Salario Base:</label>
                            @php
                                $salario_base = $contract->employee->salario_base ?? 0;
                            @endphp
                            <input type="text" value="${{ number_format($salario_base, 0, ',', '.') }}" disabled 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                        </div>
                        
                        {{-- Cargo (Solo lectura/deshabilitado en el select, valor en hidden) --}}
                        <div class="col-span-3 sm:col-span-3">
                            <label for="job_title_id_display" class="block text-sm font-medium text-gray-700">Cargo *:</label>
                            <input type="text" id="job_title_id_display" value="{{ $contract->jobTitle->nombre ?? 'Cargo No Encontrado' }}" disabled 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                            
                            {{-- CAMPOS OCULTOS CRUCIALES PARA LA ACTUALIZACIÓN --}}
                            <input type="hidden" name="job_title_id" value="{{ $contract->job_title_id }}">
                            <input type="hidden" name="employee_id" value="{{ $contract->employee_id }}">
                        </div>
                    </div>

                    <div class="my-6 border-t border-gray-200"></div>

                    {{-- ===================================================================
                        2. VIGENCIA Y TIPO DE CONTRATO (Editables)
                        =================================================================== --}}
                    <h2 class="text-xl font-semibold mb-4 text-indigo-600">
                        Vigencia y Tipo de Contrato
                    </h2>

                    {{-- CORRECCIÓN: Se agrega el campo numero_contrato --}}
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6"> 
                        
                        {{-- Número de Contrato (NUEVO CAMPO REQUERIDO) --}}
                        <div>
                            <label for="numero_contrato" class="block text-sm font-medium text-gray-700">Número de Contrato *:</label>
                            <input type="text" name="numero_contrato" id="numero_contrato" 
                                value="{{ old('numero_contrato', $contract->numero_contrato) }}" 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('numero_contrato') border-red-500 @enderror">
                            @error('numero_contrato') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fecha de Inicio --}}
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio *:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                value="{{ old('fecha_inicio', \Carbon\Carbon::parse($contract->fecha_inicio)->format('Y-m-d')) }}" 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('fecha_inicio') border-red-500 @enderror">
                             @error('fecha_inicio') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Fecha Final --}}
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Final (si aplica):</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" 
                                value="{{ old('fecha_fin', $contract->fecha_fin ? \Carbon\Carbon::parse($contract->fecha_fin)->format('Y-m-d') : null) }}" 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('fecha_fin') border-red-500 @enderror">
                            @error('fecha_fin') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tipo de Contrato --}}
                        <div>
                            <label for="contract_type_id" class="block text-sm font-medium text-gray-700">Tipo de Contrato *:</label>
                            <select name="contract_type_id" id="contract_type_id" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('contract_type_id') border-red-500 @enderror">
                                @foreach ($contractTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('contract_type_id', $contract->contract_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_type_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Grupo de Nómina --}}
                        <div>
                            <label for="grupo_nomina" class="block text-sm font-medium text-gray-700">Grupo de Nómina *:</label>
                            <select name="grupo_nomina" id="grupo_nomina" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('grupo_nomina') border-red-500 @enderror">
                                {{-- Asegúrate que estas opciones coincidan con tu validación --}}
                                @foreach (['FIJO-ADMINISTRATIVO', 'FIJO-OPERATIVO', 'OBRA-LABOR'] as $grupo)
                                    <option value="{{ $grupo }}" 
                                        {{ old('grupo_nomina', $contract->grupo_nomina) == $grupo ? 'selected' : '' }}>
                                        {{ $grupo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grupo_nomina') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="my-6 border-t border-gray-200"></div>

                    {{-- ===================================================================
                        3. ACUERDOS Y DESCUENTOS (Editables)
                        =================================================================== --}}
                    <h2 class="text-xl font-semibold mb-4 text-indigo-600">
                        Acuerdos y Descuentos
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Tipo de Acuerdo Laboral --}}
                        <div>
                            <label for="tipo_acuerdo_laboral" class="block text-sm font-medium text-gray-700">Tipo de Acuerdo Laboral *:</label>
                            <select name="tipo_acuerdo_laboral" id="tipo_acuerdo_laboral" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('tipo_acuerdo_laboral') border-red-500 @enderror">
                                @foreach (['LEGAL', 'SINDICAL', 'CONVENCIONAL'] as $tipo)
                                    <option value="{{ $tipo }}" 
                                        {{ old('tipo_acuerdo_laboral', $contract->tipo_acuerdo_laboral) == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_acuerdo_laboral') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Descuento Sindical --}}
                        <div>
                            <label for="descuento_sindical" class="block text-sm font-medium text-gray-700">Descuento Sindical *:</label>
                            <select name="descuento_sindical" id="descuento_sindical" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('descuento_sindical') border-red-500 @enderror">
                                {{-- Usar valores 0 y 1 para booleanos --}}
                                <option value="0" {{ old('descuento_sindical', $contract->descuento_sindical) == false ? 'selected' : '' }}>NO</option>
                                <option value="1" {{ old('descuento_sindical', $contract->descuento_sindical) == true ? 'selected' : '' }}>SÍ</option>
                            </select>
                            @error('descuento_sindical') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Acuerdo Laboral (Si aplica) --}}
                        <div>
                            <label for="acuerdo_laboral" class="block text-sm font-medium text-gray-700">Acuerdo Laboral (Si aplica) *:</label>
                            <select name="acuerdo_laboral" id="acuerdo_laboral" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('acuerdo_laboral') border-red-500 @enderror">
                                <option value="">Seleccione...</option>
                                @foreach ($acuerdos as $acuerdo)
                                    <option value="{{ $acuerdo->nombre }}" 
                                        {{ old('acuerdo_laboral', $contract->acuerdo_laboral) == $acuerdo->nombre ? 'selected' : '' }}>
                                        {{ $acuerdo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('acuerdo_laboral') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="my-6 border-t border-gray-200"></div>

                    {{-- ===================================================================
                        4. ENTIDADES DE RIESGO Y SALUD
                        =================================================================== --}}
                    <h2 class="text-xl font-semibold mb-4 text-indigo-600">
                        Entidades de Riesgo y Salud
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        {{-- EPS --}}
                        <div>
                            <label for="eps_id" class="block text-sm font-medium text-gray-700">EPS *:</label>
                            <select name="eps_id" id="eps_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('eps_id') border-red-500 @enderror">
                                @foreach ($epsList as $eps)
                                    <option value="{{ $eps->id }}" 
                                        {{ old('eps_id', $contract->eps_id) == $eps->id ? 'selected' : '' }}>
                                        {{ $eps->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('eps_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- ARL --}}
                        <div>
                            <label for="arl_entity_id" class="block text-sm font-medium text-gray-700">ARL *:</label>
                            <select name="arl_entity_id" id="arl_entity_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('arl_entity_id') border-red-500 @enderror">
                                @foreach ($arlList as $arl)
                                    <option value="{{ $arl->id }}" 
                                        {{ old('arl_entity_id', $contract->arl_entity_id) == $arl->id ? 'selected' : '' }}>
                                        {{ $arl->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('arl_entity_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nivel de Riesgo --}}
                        <div>
                            <label for="risk_level_id" class="block text-sm font-medium text-gray-700">Nivel de Riesgo *:</label>
                            <select name="risk_level_id" id="risk_level_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('risk_level_id') border-red-500 @enderror">
                                @foreach ($riskLevels as $level)
                                    <option value="{{ $level->id }}" 
                                        {{ old('risk_level_id', $contract->risk_level_id) == $level->id ? 'selected' : '' }}>
                                        {{ $level->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Corrección de sintaxis aquí --}}
                            @error('risk_level_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror 
                        </div>

                        {{-- Caja de Compensación --}}
                        <div>
                            <label for="compensation_fund_id" class="block text-sm font-medium text-gray-700">Caja de Compensación *:</label>
                            <select name="compensation_fund_id" id="compensation_fund_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('compensation_fund_id') border-red-500 @enderror">
                                @foreach ($compensationFunds as $fund)
                                    <option value="{{ $fund->id }}" 
                                        {{ old('compensation_fund_id', $contract->compensation_fund_id) == $fund->id ? 'selected' : '' }}>
                                        {{ $fund->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('compensation_fund_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="my-6 border-t border-gray-200"></div>
                    
                    {{-- ===================================================================
                        5. FONDOS DE AHORRO
                        =================================================================== --}}
                    <h2 class="text-xl font-semibold mb-4 text-indigo-600">
                        Fondos de Ahorro
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Fondo de Pensión --}}
                        <div>
                            <label for="pension_fund_id" class="block text-sm font-medium text-gray-700">Fondo de Pensión *:</label>
                            <select name="pension_fund_id" id="pension_fund_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('pension_fund_id') border-red-500 @enderror">
                                @foreach ($pensionFunds as $fund)
                                    <option value="{{ $fund->id }}" 
                                        {{ old('pension_fund_id', $contract->pension_fund_id) == $fund->id ? 'selected' : '' }}>
                                        {{ $fund->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pension_fund_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fondo de Cesantías --}}
                        <div>
                            <label for="cesantias_fund_id" class="block text-sm font-medium text-gray-700">Fondo de Cesantías *:</label>
                            <select name="cesantias_fund_id" id="cesantias_fund_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('cesantias_fund_id') border-red-500 @enderror">
                                @foreach ($cesantiasFunds as $fund)
                                    <option value="{{ $fund->id }}" 
                                        {{ old('cesantias_fund_id', $contract->cesantias_fund_id) == $fund->id ? 'selected' : '' }}>
                                        {{ $fund->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cesantias_fund_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Campos ocultos para referencia en la actualización --}}
                    <input type="hidden" name="estado_contrato" value="{{ $contract->estado_contrato }}">
                    <input type="hidden" name="fecha_cambio_estado" value="{{ $contract->fecha_cambio_estado }}">
                </div>

                {{-- ===================================================================
                    BOTÓN DE GUARDAR Y CANCELAR
                    =================================================================== --}}
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('contracts.show', $contract) }}" class="inline-flex justify-center rounded-md border border-gray-300 py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                        Volver a los Detalles
                    </a>
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Actualizar Contrato
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection