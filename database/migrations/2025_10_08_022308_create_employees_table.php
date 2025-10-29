<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // LLAMADA ÚNICA Y CORRECTA
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('identificacion')->unique();

            // Nuevos campos de RRHH y Legales
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F', 'O'])->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('direccion')->nullable();
            
            // CAMPO DE FOTO
            $table->string('foto_path')->nullable(); 

            $table->string('cargo');
            $table->decimal('salario_base', 10, 2); 
            $table->date('fecha_ingreso');
            $table->timestamps();
        }); // Cierre correcto de Schema::create
    }

    
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}; 
