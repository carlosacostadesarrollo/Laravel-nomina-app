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
        Schema::create('configuracions', function (Blueprint $table) {
            $table->id();
            
            // Datos de la Empresa
            $table->string('nombre_empresa', 150);
            $table->string('nit', 30)->unique();
            $table->string('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email_contacto')->nullable();

            // Parámetros Financieros y Legales
            $table->string('moneda_base', 5)->default('COP'); 
            $table->decimal('salario_minimo_legal', 10, 2); 
            $table->decimal('porcentaje_arl', 5, 2); 
            $table->decimal('porcentaje_salud_empresa', 5, 2);
            $table->decimal('porcentaje_pension_empresa', 5, 2);

            // Parámetros de Operación
            $table->date('fecha_inicio_periodo');
            $table->integer('dias_periodo_nomina'); // 15 o 30

            // Opcionales
            $table->string('logo_path')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracions');
    }
};
