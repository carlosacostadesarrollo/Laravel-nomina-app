<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arl extends Model
{
    use HasFactory;
    
    // Indica a Laravel que el nombre de la tabla es 'arls'
    protected $table = 'arls'; 

    // Define los campos que se pueden llenar masivamente (necesario para los seeders)
    protected $fillable = [
        'nombre', 
        'nit', 
        'nivel_riesgo', 
        'porcentaje'
    ];

    // Define los tipos de datos para asegurar el formato (opcional pero recomendado)
    protected $casts = [
        'porcentaje' => 'decimal:5', 
    ];
}
