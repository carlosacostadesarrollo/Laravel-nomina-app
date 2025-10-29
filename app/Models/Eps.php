<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    use HasFactory;
    
    // 1. Verificar que apunte a la tabla correcta
    protected $table = 'eps'; 
    
    // 2. IMPORTANTE: Asegurarse de que 'nombre' y 'nit' estén en fillable
    protected $fillable = ['nombre', 'nit'];
    
    // Si no usas $fillable, asegurate que $guarded esté vacío:
    // protected $guarded = []; 
}