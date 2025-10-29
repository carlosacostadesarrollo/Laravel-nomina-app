<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Se cambia a true para permitir el acceso al controlador.
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // La validación se construye para coincidir con todos los campos de contracts/create.blade.php
        return [
            // -----------------------------------------------------
            // SECCIÓN DINÁMICA: EMPLEADO (Viene del input hidden)
            // -----------------------------------------------------
            'employee_id' => ['required', 'exists:employees,id'],
            
            // -----------------------------------------------------
            // DATOS DEL EMPLEADO (Solo se validan las FK que vienen del formulario)
            // -----------------------------------------------------
            'job_title_id' => ['required', 'exists:job_titles,id'],
            'salario' => ['required', 'numeric', 'min:100000'], // Asegurando un valor mínimo positivo
            
            // -----------------------------------------------------
            // DATOS DEL CONTRATO
            // -----------------------------------------------------
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after:fecha_inicio'],
            
            'contract_type_id' => ['required', 'exists:contract_types,id'],
            
            // Grupos de selección libre (validar que sean opciones válidas)
            'grupo_nomina' => ['required', Rule::in(['FIJO-ADMINISTRATIVO', 'VARIABLE-OPERACIONAL'])],
            'tipo_acuerdo_laboral' => ['required', Rule::in(['LEGAL', 'SINDICATOS', 'RESOLUCION'])],
            'acuerdo_laboral_id' => ['nullable', 'integer'], // Si es una FK de una tabla, usar 'exists' en su lugar.
            'descuento_sindical' => ['required', 'in:0,1'],

            // -----------------------------------------------------
            // SEGURIDAD SOCIAL Y FONDOS (Algunos campos permiten NO APLICA / null)
            // -----------------------------------------------------
            
            // Las FK que aceptan 'NO APLICA' deben ser 'nullable' y deben usar 'exists' si se proporciona un valor.
            // Nota: Si el valor vacío ('') se envía y la columna en DB es NULL, 'nullable' lo acepta.
            'eps_id' => ['nullable', 'exists:eps,id'],
            'arl_entity_id' => ['nullable', 'exists:arl_entities,id'],
            'risk_level_id' => ['nullable', 'exists:risk_levels,id'], // Nivel de riesgo ARL
            'pension_fund_id' => ['nullable', 'exists:pension_funds,id'],
            'cesantias_fund_id' => ['nullable', 'exists:cesantias_funds,id'],
            
            // Caja de Compensación (Este campo no permite NO APLICA)
            'compensation_fund_id' => ['required', 'exists:compensation_funds,id'],

            // -----------------------------------------------------
            // ESTADO Y FINALIZACIÓN
            // -----------------------------------------------------
            'estado_contrato' => ['required', Rule::in(['ACTIVO', 'FINALIZADO'])],
            'fecha_cambio_estado' => ['nullable', 'date'],
            'motivo_finalizacion' => ['required', Rule::in(['LABORALMENTE ACTIVO', 'RENUNCIA_VOLUNTARIA', 'TERMINACION_JUSTA_CAUSA'])],
        ];
    }
}

