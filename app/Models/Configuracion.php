<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;
    
    // Especificar el nombre de la tabla (Laravel lo haría automáticamente, pero es mejor ser explícito)
    protected $table = 'configuracions'; 
    
    // Definir los campos que se pueden asignar masivamente (Mass Assignment)
    protected $fillable = [
        'nombre_empresa',
        'nit',
        'direccion',
        'telefono',
        'email_contacto',
        'moneda_base',
        'salario_minimo_legal',
        'porcentaje_arl',
        'porcentaje_salud_empresa',
        'porcentaje_pension_empresa',
        'fecha_inicio_periodo',
        'dias_periodo_nomina',
        'logo_path',
    ];
}
