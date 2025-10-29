<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskLevel extends Model
{
    use HasFactory;
    
    // Asigna la tabla correcta en la base de datos
    protected $table = 'risk_levels'; 
    
    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'porcentaje']; 
    
    // Asegura que 'porcentaje' se maneje como un decimal con 5 cifras de precisión
    protected $casts = [
        'porcentaje' => 'decimal:5', 
    ];

    /**
     * Un Nivel de Riesgo (Clase I-V) puede estar asociado a muchos Contratos.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}