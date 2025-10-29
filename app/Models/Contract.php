<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Employee;    
use App\Models\JobTitle;    
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;

    // Define la tabla si no sigue la convención estándar (opcional si es 'contracts')
    protected $table = 'contracts'; 

    // Los campos que pueden ser asignados masivamente (desde el formulario)
    protected $fillable = [
        'employee_id',
        'numero_contrato',
        'fecha_inicio',
        'fecha_fin',
        'salario', // ¡AÑADIDO! Campo crucial que faltaba
        'contract_type_id', 
        'grupo_nomina',
        'job_title_id', 
        'tipo_acuerdo_laboral',
        'acuerdo_laboral',
        'descuento_sindical',
        'pension_fund_id', 
        'eps_id', 
        
        // ¡CORRECCIÓN ARL! Eliminamos 'arl_id' y lo reemplazamos por las dos nuevas FKs
        'arl_entity_id',        // Nueva FK a la tabla de entidades (las 10 ARL)
        'risk_level_id',        // Nueva FK a la tabla de niveles de riesgo (Clase I-V)
        
        'compensation_fund_id', 
        'cesantias_fund_id',    
        'estado_contrato',
        'fecha_cambio_estado',
        'motivo_finalizacion',
    ];

    // Define los campos que deben ser tratados como fechas
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_cambio_estado' => 'date',
        'descuento_sindical' => 'boolean', // Convierte 0/1 a true/false
        'salario' => 'decimal:2', // Recomendado para manejar el salario como decimal
    ];

     // Método que se ejecuta al inicio de la vida del modelo.
    protected static function boot()
    {
        parent::boot();

        // Escucha el evento 'created', que se dispara DESPUÉS de la inserción y cuando el ID existe.
        static::created(function ($contract) {
            
            // 1. Obtener el ID recién creado (Ej: 2)
            $newId = $contract->id;
            
            // 2. Formatear el consecutivo (rellenar con ceros hasta 5 dígitos)
            $consecutivo = str_pad($newId, 5, '0', STR_PAD_LEFT);
            
            // 3. Crear el número de contrato final (Asegúrate de que el prefijo sea el correcto, Ej: DOM-)
            $numeroContrato = "DOM-{$consecutivo}"; 

            // 4. Asignar el valor y actualizar el mismo registro sin disparar este evento de nuevo
            $contract->numero_contrato = $numeroContrato;
            $contract->saveQuietly(); 
            
            // NOTA: Si necesitas que el número de contrato sea el mismo que el existente
            // en la imagen (DOM-00001), verifica si el prefijo "DOM" es fijo o dinámico.
            // Aquí lo dejamos fijo como "DOM".
        });
    }

    // El contrato pertenece a un empleado
    public function employee(): BelongsTo
    {
       return $this->belongsTo(Employee::class, 'employee_id');
    }

    // El contrato está asociado a un cargo
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }

    // Relación con el tipo de contrato
    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    //  Accessor para obtener el Salario Base Formateado del Empleado
    protected function salarioFormateado(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Asegúrate de que la relación 'employee' esté cargada
                if ($this->relationLoaded('employee') && $this->employee) {
                    return '$ ' . number_format($this->employee->salario_base, 0, ',', '.');
                }
                return 'N/A'; // Si no hay empleado asociado o salario
            }
        );
    }
    
    //Accessor para formatear la Fecha de Inicio
    protected function fechaInicioFormateada(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->fecha_inicio)->format('d/m/Y')
        );
    }
    
    // Accessor para formatear la Fecha Fin (si es null, mostrar 'Indefinido')
    protected function fechaFinFormateada(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->fecha_fin) {
                    return Carbon::parse($this->fecha_fin)->format('d/m/Y');
                }
                return 'Indefinido';
            }
        );
    }
    // Relación con el fondo de pensiones
    public function pensionFund(): BelongsTo
    {
        return $this->belongsTo(PensionFund::class);
    }

    // Relación con la EPS
    public function eps(): BelongsTo
    {
        return $this->belongsTo(Eps::class);
    }

    // ¡CORRECCIÓN ARL!
    // Relación con la ENTIDAD ARL (nueva tabla 'arl_entities')
    public function arlEntity(): BelongsTo
    {
        return $this->belongsTo(ArlEntity::class, 'arl_entity_id');
    }
    
    // ¡NUEVA RELACIÓN!
    // Relación con el NIVEL DE RIESGO (nueva tabla 'risk_levels')
    public function riskLevel(): BelongsTo
    {
        return $this->belongsTo(RiskLevel::class, 'risk_level_id');
    }

    // Relación con la Caja de Compensación
    public function compensationFund(): BelongsTo
    {
        return $this->belongsTo(CompensationFund::class);
    }

    // Relación con el Fondo de Cesantías
    public function cesantiasFund(): BelongsTo
    {
        return $this->belongsTo(CesantiasFund::class);
    }
}