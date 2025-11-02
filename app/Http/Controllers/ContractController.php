<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest; // Importar el Form Request
use App\Models\Contract; // Importar el Modelo principal
use App\Models\Eps;
use App\Models\PensionFund;
use App\Models\CompensationFund;
use App\Models\CesantiasFund;
use App\Models\ArlEntity;
use App\Models\RiskLevel;
use App\Models\ContractType;
use App\Models\JobTitle;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para transacciones si es necesario
use Illuminate\Support\Facades\Log; // Para registrar errores
use App\Models\Payment; // Importa el modelo de la tabla que tiene dependencia

/**
 * Elimina el contrato especificado del almacenamiento (por ID).
 *
 * @param  \App\Models\Contract  $contract (Inyección de modelo)
 * @return \Illuminate\Http\Response
 */



class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Se utiliza la convención con la carga de relaciones para un mejor rendimiento
        $contracts = Contract::with(['employee', 'jobTitle'])->get(); 
        return view('contracts.index', compact('contracts'));
    }

    // --- MÉTODOS DE VISTA ---

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Carga de datos para los selectores de la vista create.blade.php
        $jobTitles = JobTitle::all();
        $contractTypes = ContractType::all();
        $epsEntities = Eps::all();
        $pensionFunds = PensionFund::all();
        $compensationFunds = CompensationFund::all();
        $cesantiasFunds = CesantiasFund::all();
        $arlEntities = ArlEntity::all();
        $riskLevels = RiskLevel::all();

        // Se utiliza la función compact() para pasar las variables a la vista
        return view('contracts.create', compact(
            'jobTitles', 
            'contractTypes', 
            'epsEntities', 
            'pensionFunds', 
            'compensationFunds', 
            'cesantiasFunds', 
            'arlEntities', 
            'riskLevels'
        ));
    }

    // --- MÉTODO DE GUARDADO (STORE) ---

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request) 
    {
        // La validación se realiza automáticamente por StoreContractRequest.
        // Si falla, retorna 422, que el JS del frontend capturará.
        $validatedData = $request->validated();
        
        // 1. Obtener los datos validados y excluir 'salario' y 'identificacion' (que son solo para la búsqueda/display).
        $dataToStore = collect($validatedData)->except(['salario', 'identificacion'])->toArray();
        
        // 2. Mapear 'descuento_sindical' del valor del formulario a un booleano (0 o 1).
        // Si el valor es '1' (que es el valor que tiene el SELECT del frontend para SÍ), lo mantiene como 1 (true).
        // Si el valor es '0' (NO), lo mantiene como 0 (false).
        $dataToStore['descuento_sindical'] = (int)$dataToStore['descuento_sindical'];

        // 3. Iniciar Transacción para asegurar la consistencia del número de contrato.
        try {
            DB::beginTransaction();
            
            // 4. Crear el contrato sin el número final para obtener el ID.
            $contract = Contract::create($dataToStore);
            
            // 5. Generar el número de contrato basado en el ID recién insertado.
            // Asegúrese de que el campo 'numero_contrato' pueda ser NULL temporalmente
            // o que tenga un valor por defecto en la BD para evitar errores NOT NULL.
            // Si no puede ser NULL, use el método save() dos veces.
            $contract->numero_contrato = 'CON-' . str_pad($contract->id, 5, '0', STR_PAD_LEFT);
            $contract->save();

            DB::commit();

            // 6. Respuesta JSON de Éxito para el fetch/AJAX
            return response()->json([
                'message' => 'Contrato creado exitosamente',
                'contract' => $contract,
                'redirect' => route('contracts.index')
            ], 201); // Código 201 Created

        } catch (\Exception $e) {
            DB::rollBack();
            // Registrar el error detallado en los logs de Laravel
            Log::error('Error al guardar el contrato: ' . $e->getMessage() . ' en línea ' . $e->getLine());
            
            // Respuesta JSON de Error 500 para el fetch/AJAX
            return response()->json([
                'message' => 'Error en el servidor al guardar el contrato. Por favor, revise los logs.',
                'error_detail' => $e->getMessage() // Detalle del error para depuración
            ], 500);
        }
    }



    // --- OTROS MÉTODOS CRUD (sin cambios) ---


    public function edit(Contract $contract)
   {
        // Cargar todas las colecciones necesarias para los SELECTs del formulario
        $contractTypes = ContractType::all();
        $epsList = Eps::all();
        $arlList = ArlEntity::all();
        $riskLevels = RiskLevel::all();
        // Ejemplo: Si tienes modelos para los fondos y acuerdos
        $pensionFunds = PensionFund::all();
        $cesantiasFunds = CesantiasFund::all();
        $compensationFunds = CompensationFund::all();
        
        $acuerdos = [
        (object)['nombre' => 'LEGAL'], 
        (object)['nombre' => 'SINDICATOS'], 
        (object)['nombre' => 'RESOLUCION'], 
        // Añade aquí todos los acuerdos necesarios
       ];
        
        // Devolver la vista de edición con el contrato y todas las colecciones
        return view('contracts.edit', compact(
            'contract', 
            'contractTypes', 
            'epsList', 
            'arlList', 
            'riskLevels',
            'pensionFunds',
            'cesantiasFunds',
            'compensationFunds',
            'acuerdos'
        ));
    }

     public function show(Contract $contract)
    {
        // El modelo Contract ya está cargado con las relaciones básicas gracias a la Inyección de Modelo.
        // Si necesitas cargar relaciones específicas (como todas las entidades de salud/riesgo)
        // en esta vista de solo lectura, puedes forzar la carga:
        /*
        $contract->load([
            'employee', 'jobTitle', 'contractType', 'eps', 'arlEntity', 
            'riskLevel', 'pensionFund', 'cesantiasFund'
        ]);
        */
        
        // Retornar la vista 'contracts.show' pasando la variable $contract
        return view('contracts.show', compact('contract'));
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        // 1. Definición de las reglas de validación
    $rules = [
        // Datos de vigencia y tipo de contrato
        'fecha_inicio' => ['required', 'date'],
        'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        'contract_type_id' => ['required', 'exists:contract_types,id'],
        'grupo_nomina' => ['required', 'in:FIJO-ADMINISTRATIVO,FIJO-OPERATIVO,OBRA-LABOR'],
        'numero_contrato' => 'required|string|max:255|unique:contracts,numero_contrato,' . $contract->id,

        // Acuerdos y descuentos
        'tipo_acuerdo_laboral' => ['required', 'in:LEGAL,SINDICAL,CONVENCIONAL'],
        'descuento_sindical' => ['required', 'boolean'],
        'acuerdo_laboral' => ['nullable', 'string', 'max:255'], 

        // Entidades de riesgo y salud
        'eps_id' => ['required', 'exists:eps,id'],
        'arl_entity_id' => ['required', 'exists:arl_entities,id'],
        'risk_level_id' => ['required', 'exists:risk_levels,id'],
        'compensation_fund_id' => ['required', 'exists:compensation_funds,id'],
        
        // Fondos de ahorro
        'pension_fund_id' => ['required', 'exists:pension_funds,id'],
        'cesantias_fund_id' => ['required', 'exists:cesantias_funds,id'],
        
        // Campos de solo lectura (pasados como hidden)
        'employee_id' => ['required', 'exists:employees,id'],
        'job_title_id' => ['required', 'exists:job_titles,id'],
    ];

    // 2. Ejecutar la validación
    $validatedData = $request->validate($rules);

    // 3. Actualización del modelo Contract
    $contract->update($validatedData);
    
    // 4. Redirección con mensaje de éxito (asumiendo que contracts.show existe y muestra el detalle)
    return redirect()->route('contracts.show', $contract)->with('success', '✅ ¡El contrato ha sido actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
            {
                // Iniciar la transacción de base de datos para garantizar la integridad.
            DB::beginTransaction();

            try {
                // 1. VALIDACIÓN DE DEPENDENCIAS (Integridad Referencial Lógica)
                
                // Verifica si existen pagos, nóminas o cualquier otro registro 
                // que use el ID de este contrato (contract->id).
                // NOTA: Para que esto funcione, la relación 'payments()' debe estar definida en el modelo Contract.
                if ($contract->payments()->exists()) {
                    DB::rollBack();
                    $numeroContrato = $contract->numero_contrato; // Usamos el numero_contrato para un mejor mensaje

                    return redirect()->route('contracts.index')
                                    ->with('error', '⚠️ **Error:** No se puede eliminar el contrato N° ' . $numeroContrato . ' porque ya tiene registros de nómina o pagos asociados. Elimine las dependencias primero.');
                }

                // 2. ELIMINACIÓN
                
                $numeroContrato = $contract->numero_contrato;
                $contract->delete();

                // 3. Confirmar la transacción si todo fue exitoso.
                DB::commit();

                // 4. REDIRECCIÓN
                return redirect()->route('contracts.index')
                                ->with('success', '🗑️ El contrato N° ' . $numeroContrato . ' ha sido eliminado exitosamente.');

            } catch (\Exception $e) {
                // En caso de cualquier fallo (incluyendo errores de Clave Foránea de BD si se usó RESTRICT)
                DB::rollBack();

                return redirect()->route('contracts.index')
                                ->with('error', '❌ Error al intentar eliminar el contrato. Asegúrese de que no haya registros dependientes.');
            }
        }
}
