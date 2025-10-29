<?php

// database/migrations/xxxx_create_risk_levels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_levels', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Clase I (Mínimo)
            // Se usa decimal para garantizar la precisión necesaria (5 dígitos después del punto)
            $table->decimal('porcentaje', 7, 5); // Ej: 0.00522
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_levels');
    }
};