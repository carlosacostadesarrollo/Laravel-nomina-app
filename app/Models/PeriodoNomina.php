<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoNomina extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'periodos_nomina';

    /**
     * Los atributos que son asignables en masa.
     * @var array
     */
    protected $fillable = [
        'anio',
        'mes',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];
    
    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * @var array
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
}