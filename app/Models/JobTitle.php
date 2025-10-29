<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;
    
    // **AÑADE ESTA PROPIEDAD**
    protected $fillable = [
        'nombre', 
        'salario_base',
    ];
}
