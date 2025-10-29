@extends('layouts.app')

@section('content')

<div class="py-12"> 
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"> 

            <h2 class="text-2xl font-semibold leading-tight mb-6 text-indigo-700 border-b pb-2">Crear Nuevo Contrato</h2>
            
            {{-- DIV para mostrar errores de AJAX --}}
            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error:</strong>
                <span id="error-text" class="block sm:inline"></span>
            </div>
            
            <form action="{{ route('contracts.store') }}" method="POST" id="contractForm">
                @csrf
                
                {{-- Campos ocultos para datos de empleado (employee_id es el id de la tabla employees) --}}
                <input type="hidden" name="employee_id" id="employee_id_hidden" required> 
                <input type="hidden" name="salario" id="salario_hidden" required> 
                
                {{-- SECCIÓN 1: DATOS DE IDENTIFICACIÓN, CARGO Y SALARIO --}}
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2">Datos de Identificación, Cargo y Salario</h5>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        {{-- Número de Identificación --}}
                        <div>
                            <label for="identificacion" class="block font-medium text-sm text-gray-700">Número de Identificación *:</label>
                            <input type="text" name="identificacion" id="identificacion" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" placeholder="Ingrese la identificación" required>
                            @error('identificacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Nombres Completos (Solo lectura) --}}
                        <div>
                            <label for="nombres_completos" class="block font-medium text-sm text-gray-700">Nombres Completos:</label>
                            <input type="text" id="nombres_completos" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full bg-gray-100 p-2" readonly placeholder="El nombre aparecerá aquí">
                        </div>

                        {{-- Salario Base (Solo lectura) --}}
                        <div>
                            <label for="salario_display" class="block font-medium text-sm text-gray-700">Salario Base:</label>
                            <input type="text" id="salario_display" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full bg-gray-100 p-2" readonly placeholder="Salario del cargo">
                        </div>
                        
                        {{-- Cargo --}}
                        <div class="md:col-span-1">
                            <label for="job_title_id" class="block font-medium text-sm text-gray-700">Cargo *:</label>
                            <select name="job_title_id" id="job_title_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione un Cargo</option>
                                @isset($jobTitles)
                                    @foreach($jobTitles as $title)
                                        <option value="{{ $title->id }}">{{ $title->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('job_title_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                    </div>
                </div>

                {{-- SECCIÓN 2: VIGENCIA Y TIPO DE CONTRATO --}}
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2">Vigencia y Tipo de Contrato</h5>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6"> 

                        {{-- Fecha de Inicio --}}
                        <div>
                            <label for="fecha_inicio" class="block font-medium text-sm text-gray-700">Fecha de Inicio *:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                            @error('fecha_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fecha de Fin --}}
                        <div>
                            <label for="fecha_fin" class="block font-medium text-sm text-gray-700">Fecha Final (si aplica):</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                            @error('fecha_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tipo de Contrato --}}
                        <div>
                            <label for="contract_type_id" class="block font-medium text-sm text-gray-700">Tipo de Contrato *:</label> 
                            <select name="contract_type_id" id="contract_type_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required> 
                                <option value="">Seleccione Tipo</option>
                                @isset($contractTypes)
                                    @foreach($contractTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('contract_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Grupo de Nómina (LISTA FIJA) --}}
                        <div>
                            <label for="grupo_nomina" class="block font-medium text-sm text-gray-700">Grupo de Nómina *:</label>
                            <select name="grupo_nomina" id="grupo_nomina" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione Grupo</option>
                                <option value="FIJO-ADMINISTRATIVO">FIJO-ADMINISTRATIVO</option>
                                <option value="INDEFINIDO-ADMINISTRATIVO">INDEFINIDO-ADMINISTRATIVO</option>
                                <option value="FIJO-APRENDIZ">FIJO-APRENDIZ</option>
                                <option value="FIJO-CONVENCIONAL">FIJO-CONVENCIONAL</option>
                                <option value="INDEFINIDO-CONVENCIONAL">INDEFINIDO-CONVENCIONAL</option>
                                <option value="NO APLICA">NO APLICA</option> 
                            </select>
                            @error('grupo_nomina') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                {{-- SECCIÓN 3: ENTIDADES DE RIESGO Y SALUD --}}
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2">Entidades de Riesgo y Salud</h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> 

                        {{-- Entidad EPS --}}
                        <div>
                            <label for="eps_id" class="block font-medium text-sm text-gray-700">Entidad EPS *:</label>
                            <select name="eps_id" id="eps_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione una EPS</option>
                                <option value="0">NO APLICA</option> {{-- Usamos 0 para 'NO APLICA' --}}
                                @isset($epsEntities)
                                    @foreach($epsEntities as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('eps_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Entidad ARL --}}
                        <div>
                            <label for="arl_id" class="block font-medium text-sm text-gray-700">Entidad ARL *:</label>
                            <select name="arl_entity_id" id="arl_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione una ARL</option>
                                <option value="0">NO APLICA</option> {{-- Usamos 0 para 'NO APLICA' --}}
                                @isset($arlEntities)
                                    @foreach($arlEntities as $a)
                                        <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('arl_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nivel de Riesgo ARL --}}
                        <div>
                            <label for="risk_level_id" class="block font-medium text-sm text-gray-700">Nivel de Riesgo ARL *:</label>
                            <select name="risk_level_id" id="risk_level_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione Nivel</option>
                                @isset($riskLevels)
                                    @foreach($riskLevels as $risk)
                                        <option value="{{ $risk->id }}">{{ $risk->nombre }} ({{ number_format($risk->porcentaje * 100, 2) }}%)</option> 
                                    @endforeach
                                @endisset
                            </select>
                            @error('risk_level_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>
                
                {{-- SECCIÓN 4: FONDOS Y ACUERDOS LABORALES --}}
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2">Fondos de Ahorro y Acuerdos Sindicales</h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                        {{-- Fondo de Pensiones --}}
                        <div>
                            <label for="pension_fund_id" class="block font-medium text-sm text-gray-700">Fondo de Pensiones *:</label>
                            <select name="pension_fund_id" id="pension_fund_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione Fondo</option>
                                @isset($pensionFunds)
                                    @foreach($pensionFunds as $fund)
                                        <option value="{{ $fund->id }}">{{ $fund->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('pension_fund_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Fondo de Cesantías --}}
                        <div>
                            <label for="cesantias_fund_id" class="block font-medium text-sm text-gray-700">Fondo de Cesantías *:</label>
                            <select name="cesantias_fund_id" id="cesantias_fund_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione Fondo</option>
                                @isset($cesantiasFunds)
                                    @foreach($cesantiasFunds as $fund)
                                        <option value="{{ $fund->id }}">{{ $fund->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('cesantias_fund_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Caja de Compensación --}}
                        <div class="md:col-span-2">
                            <label for="compensation_fund_id" class="block font-medium text-sm text-gray-700">Caja de Compensación *:</label>
                            <select name="compensation_fund_id" id="compensation_fund_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Seleccione Caja</option>
                                @isset($compensationFunds)
                                    @foreach($compensationFunds as $fund)
                                        <option value="{{ $fund->id }}">{{ $fund->nombre }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('compensation_fund_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Tipo de Acuerdo Laboral (LISTA FIJA) --}}
                        <div>
                            <label for="tipo_acuerdo_laboral" class="block font-medium text-sm text-gray-700">Tipo de Acuerdo Laboral *:</label>
                            <select name="tipo_acuerdo_laboral" id="tipo_acuerdo_laboral" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                               
                                <option value="LEGAL">LEGAL</option>
                                <option value="SINDICATOS">SINDICATOS</option>
                                <option value="RESOLUCION">RESOLUCIÓN</option>
                            </select>
                            @error('tipo_acuerdo_laboral') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Descuento Sindical (LISTA FIJA) --}}
                        <div>
                            <label for="descuento_sindical" class="block font-medium text-sm text-gray-700">Descuento Sindical *:</label>
                            <select name="descuento_sindical" id="descuento_sindical" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="0">NO</option>
                                <option value="1">SÍ</option>
                            </select>
                            @error('descuento_sindical') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Acuerdo Laboral (Se carga y bloquea con la búsqueda) --}}
                          <div class="md:col-span-2">
                                <label for="acuerdo_laboral_id" class="block font-medium text-sm text-gray-700">Acuerdo Laboral (Si aplica) *:</label>
                                {{-- ¡CAMBIADO name="acuerdo_laboral" a name="acuerdo_laboral_id"! --}}
                                 <select name="acuerdo_laboral_id" id="acuerdo_laboral" class="..." required>
                                    <option value="0" selected>NO APLICA</option>
                                    
                                    <option value="1">SINDETRACON</option>
                                    <option value="2">SINALTRACOMFA</option>
                                    <option value="3">RESOLUCIÓN 001 DE 2005</option>
                                </select>
                                {{-- ¡CAMBIADO el error a acuerdo_laboral_id! --}}
                                @error('acuerdo_laboral_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                    </div>
                </div>

                {{-- SECCIÓN 5: DATOS DE ESTADO --}}
                <div class="mb-8">
                    <h5 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2">Gestión de Estado del Contrato</h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Estado de Contrato --}}
                        <div>
                            <label for="estado_contrato" class="block font-medium text-sm text-gray-700">Estado de Contrato *:</label>
                            <select name="estado_contrato" id="estado_contrato" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="ACTIVO" selected>ACTIVO</option> 
                                <option value="FINALIZADO">FINALIZADO</option> 
                                <option value="SUSPENDIDO">SUSPENDIDO</option>
                            </select>
                            @error('estado_contrato') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fecha de Cambio de Estado --}}
                        <div>
                            <label for="fecha_cambio_estado" class="block font-medium text-sm text-gray-700">Fecha de Cambio de Estado *:</label>
                            <input type="date" 
                                name="fecha_cambio_estado" 
                                id="fecha_cambio_estado" 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                                value="{{ now()->format('Y-m-d') }}" 
                                required>
                            @error('fecha_cambio_estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Motivo de Finalización --}}
                        <div>
                            <label for="motivo_finalizacion" class="block font-medium text-sm text-gray-700">Motivo de Finalización:</label>
                            <select name="motivo_finalizacion" id="motivo_finalizacion" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="LABORALMENTE ACTIVO" selected>LABORALMENTE ACTIVO</option> 
                                <option value="TERMINACION">TERMINACIÓN</option>
                                <option value="RENUNCIA">RENUNCIA</option>
                                <option value="DESPIDO">DESPIDO</option>
                            </select>
                            @error('motivo_finalizacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                
                {{-- Botón de Guardar --}}
                <div class="flex items-center justify-end mt-6">
                    <button type="submit" id="submitButton" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                        Guardar Contrato
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
      document.addEventListener('DOMContentLoaded', function () {
    // --- Referencias de Campos ---
    const identificacionInput = document.getElementById('identificacion');
    const nombresCompletosInput = document.getElementById('nombres_completos');
    const employeeIdHiddenInput = document.getElementById('employee_id_hidden');
    const salarioDisplayInput = document.getElementById('salario_display');
    const salarioHiddenInput = document.getElementById('salario_hidden');
    const contractForm = document.getElementById('contractForm'); // ID del formulario
    const submitButton = document.getElementById('submitButton');
    const errorMessageDiv = document.getElementById('error-message');
    const errorTextSpan = document.getElementById('error-text');
    
    // SELECTS que se rellenan y/o bloquean
    const jobTitleSelect = document.getElementById('job_title_id');
    const epsSelect = document.getElementById('eps_id');
    const arlSelect = document.getElementById('arl_id');
    const riskLevelSelect = document.getElementById('risk_level_id'); 
    const acuerdoLaboralSelect = document.getElementById('acuerdo_laboral'); 
    
    const validAcuerdoLaboralOptions = [
        'SINDETRACON', 
        'SINALTRACOMFA', 
        'RESOLUCIÓN 001 DE 2005'
    ];

    // --- Utilidades ---
    function setFieldDisabled(element, isDisabled) {
        element.disabled = isDisabled;
        if (isDisabled) {
            element.classList.add('bg-gray-100'); 
            element.classList.remove('focus:border-indigo-500', 'focus:ring-indigo-500');
        } else {
            element.classList.remove('bg-gray-100');
            element.classList.add('focus:border-indigo-500', 'focus:ring-indigo-500');
        }
    }

    function formatCurrency(number) {
        if (number === null || isNaN(number)) return '0';
        return parseFloat(number).toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).replace('COP', '').trim();
    }
    
    function clearEmployeeFields() {
        nombresCompletosInput.value = '';
        employeeIdHiddenInput.value = '';
        salarioDisplayInput.value = '';
        salarioHiddenInput.value = '';
        
        jobTitleSelect.value = '';
        setFieldDisabled(jobTitleSelect, false);
        removeHiddenJobTitle(); // Limpiar el input oculto al borrar campos

        epsSelect.value = ''; 
        setFieldDisabled(epsSelect, false);

        arlSelect.value = ''; 
        setFieldDisabled(arlSelect, false);
        
        riskLevelSelect.value = ''; 
        setFieldDisabled(riskLevelSelect, false); 

        acuerdoLaboralSelect.value = '';
        setFieldDisabled(acuerdoLaboralSelect, false); 
    }

    // ====================================================================
    // CORRECCIÓN CRÍTICA: Función auxiliar movida al ámbito global
    // Esto previene fallos de referencia que detienen la ejecución.
    // ====================================================================
    function removeHiddenJobTitle() {
        const hiddenInput = document.getElementById('job_title_id_hidden');
        if (hiddenInput) {
            hiddenInput.remove();
        }
    }
    // ====================================================================


    // --- Lógica de Búsqueda de Empleado (AJAX) ---
    async function fetchEmployeeData() {
        // La duplicación del código ha sido eliminada.
        const identificacion = identificacionInput.value.trim();
        
        if (identificacion.length < 5) {
            clearEmployeeFields();
            return;
        }

        const url = `/employees/search?cedula=${identificacion}`; 
        
        try {
            const response = await fetch(url);
            const data = await response.json();

            if (!response.ok || data.found === false) {
                clearEmployeeFields();
                removeHiddenJobTitle(); 
                errorTextSpan.textContent = 'Empleado no encontrado o error en la búsqueda.';
                errorMessageDiv.classList.remove('hidden');
                return;
            }
            
            errorMessageDiv.classList.add('hidden');
            const employee = data.employee;
            
            // ===============================================================
            // LÍNEAS CRÍTICAS DE ASIGNACIÓN DE DATOS
            // ===============================================================
            nombresCompletosInput.value = employee.nombre + ' ' + employee.apellido;
            salarioDisplayInput.value = formatCurrency(employee.salario_base || 0); 
            employeeIdHiddenInput.value = employee.id; // ¡Esto resuelve el error "Debe buscar y confirmar..."!
            salarioHiddenInput.value = employee.salario_base || 0;
            // ===============================================================

            
            // 2. Selecciona y Bloquea Cargo (Lógica del input hidden)
            if (employee.job_title_id) {
                jobTitleSelect.value = String(employee.job_title_id); 
                setFieldDisabled(jobTitleSelect, true);
                
                // LÓGICA AGREGADA: CREAR Y AÑADIR EL CAMPO OCULTO
                removeHiddenJobTitle(); 
                
                let hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'job_title_id');
                hiddenInput.setAttribute('id', 'job_title_id_hidden');
                hiddenInput.setAttribute('value', String(employee.job_title_id));
                
                jobTitleSelect.parentElement.appendChild(hiddenInput);
                
            } else {
                jobTitleSelect.value = '';
                setFieldDisabled(jobTitleSelect, false);
                removeHiddenJobTitle();
            }

            // 3-5. Entidades/Riesgo: Limpio y Habilitado para SELECCIÓN MANUAL
            epsSelect.value = ''; 
            setFieldDisabled(epsSelect, false);
            arlSelect.value = ''; 
            setFieldDisabled(arlSelect, false);
            riskLevelSelect.value = ''; 
            setFieldDisabled(riskLevelSelect, false);

            // 6. Selecciona y Bloquea Acuerdo Laboral (Esto estaba incompleto en tu fragmento original)
            const employeeAcuerdoLaboral = (employee.acuerdo_laboral || 'NO APLICA').toUpperCase().trim();
            let selectedAcuerdoLaboral = 'NO APLICA';

            if (validAcuerdoLaboralOptions.includes(employeeAcuerdoLaboral) || employeeAcuerdoLaboral === 'NO APLICA') {
                selectedAcuerdoLaboral = employeeAcuerdoLaboral;
            }
            
            acuerdoLaboralSelect.value = selectedAcuerdoLaboral;
            setFieldDisabled(acuerdoLaboralSelect, false); 
            
            
        } catch (error) {
            console.error('Error de red/servidor al buscar empleado:', error);
            errorTextSpan.textContent = 'Error de conexión al buscar el empleado. Revise la consola.';
            errorMessageDiv.classList.remove('hidden');
            clearEmployeeFields();
        }
        // El bloque `finally` no pertenece a la función fetchEmployeeData, debe estar en el manejador del `submit`.
    }

    // Eventos de búsqueda para cargar datos del empleado
    identificacionInput.addEventListener('change', fetchEmployeeData);
    identificacionInput.addEventListener('blur', fetchEmployeeData);
    
    // --- Lógica del Manejador de Envío (submit) ---
    // (Asegúrate de que esta sección exista y use la lógica del job_title_id_hidden que discutimos antes)
    // contractForm.addEventListener('submit', async function (e) { ... }) 

});
</script>
@endpush