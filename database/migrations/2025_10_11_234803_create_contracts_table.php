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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // 1. Vínculo principal
            // Un empleado puede tener varios contratos a lo largo del tiempo, pero solo uno activo.
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); 
            
            // 2. Información General del Contrato
            $table->string('numero_contrato')->unique();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // Nulo para contratos indefinidos
            
            // Tipo de Contrato (FIJO, INDEFINIDO, OBRA O LABOR, etc. - usar enum)
            $table->enum('tipo_contrato', ['FIJO', 'INDEFINIDO', 'OBRA O LABOR', 'APRENDIZAJE', 'PRACTICAS O CESANTIAS', 'TRANSITORIO']);
            
            // Grupo de Nómina (FIJO-ADMINISTRATIVO, INDEFINIDO-CONVENCIONAL, etc.)
            $table->string('grupo_nomina'); 
            
            // Cargo: Clave foránea al cargo que ya existe
            $table->foreignId('job_title_id')->constrained('job_titles')->onDelete('restrict'); 

            // 3. Acuerdos y Descuentos
            $table->enum('tipo_acuerdo_laboral', ['LEGAL', 'SINDICATOS', 'RESOLUCIÓN']);
            $table->string('acuerdo_laboral')->nullable(); // SINDETRACON, RESOLUCIÓN 001 DE 2005, NO APLICA
            $table->boolean('descuento_sindical')->default(false);

            // 4. Seguridad Social (Claves Foráneas a las tablas que ya definimos)
            // Ya hemos definido las migraciones para estas entidades
            $table->foreignId('pension_fund_id')->constrained('pension_funds')->onDelete('restrict');
            $table->foreignId('eps_id')->constrained('eps')->onDelete('restrict');
            // La ARL debe ser más compleja, ya que el riesgo depende del cargo/entorno
             
            $table->foreignId('compensation_fund_id')->constrained('compensation_funds')->onDelete('restrict');
            $table->foreignId('cesantias_fund_id')->constrained('pension_funds')->onDelete('restrict'); // Fondo de cesantías, usualmente son los mismos

            // El Nivel de Riesgo (porcentaje) se puede obtener consultando la tabla 'arls' con el arl_id

            // 5. Estado
            $table->enum('estado_contrato', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
            $table->date('fecha_cambio_estado')->nullable();
            $table->string('motivo_finalizacion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
