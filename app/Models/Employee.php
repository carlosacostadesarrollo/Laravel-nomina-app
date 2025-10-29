<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    // 1. LISTA DE CAMPOS PERMITIDOS PARA ASIGNACIÓN MASIVA
    protected $fillable = [
        'nombre',
        'apellido',
        'identificacion',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'email',
        'job_title_id', // ¡CLAVE!
        'salario_base',
        'fecha_ingreso',
        'direccion',
        'foto_path', // ¡CLAVE para la foto!
    ];

    // 2. CONVERSIÓN DE TIPOS DE DATOS (Fechas)
    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_nacimiento' => 'date',
    ];
}
