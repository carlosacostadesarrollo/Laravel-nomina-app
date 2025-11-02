<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_periodo_nominas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_nomina', function (Blueprint $table) {
            $table->id();
            // Campos de la propuesta
            $table->year('anio')->comment('Año fiscal del período.');
            $table->string('mes', 2)->comment('Mes (ej: 01, 12).'); 
            
            // Campos de fechas
            $table->date('fecha_inicio')->comment('Fecha de inicio del período de pago.');
            $table->date('fecha_fin')->comment('Fecha de fin del período de pago.');
            
            // Campo de estado
            $table->string('estado', 20)->default('Abierto')->comment('Estado del período: Abierto, Cerrado, Pagado.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_nomina');
    }
};