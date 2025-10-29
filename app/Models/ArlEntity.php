<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArlEntity extends Model
{
    use HasFactory;
    
    // Apunta a la nueva tabla simplificada
    protected $table = 'arl_entities'; 

    // Solo necesitamos el nombre y el NIT
    protected $fillable = ['nombre', 'nit'];
    
    /**
     * Define la relación inversa con los contratos.
     * Un ArlEntity puede estar en muchos Contratos.
     */
    public function contracts()
    {
        // Asumiendo que el campo en 'contracts' es 'arl_entity_id'
        return $this->hasMany(Contract::class);
    }
}