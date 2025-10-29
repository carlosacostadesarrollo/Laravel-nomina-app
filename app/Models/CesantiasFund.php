<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CesantiasFund extends Model
{
    use HasFactory;
    
    protected $table = 'cesantias_funds'; 

    protected $fillable = ['nombre', 'nit'];
}
