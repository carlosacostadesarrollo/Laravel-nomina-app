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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
