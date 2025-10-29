<?php

// En database/migrations/..._change_tipo_contrato_to_id_in_contracts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // 1. Eliminar la columna ENUM
            $table->dropColumn('tipo_contrato'); 
            
            // 2. Agregar la clave foránea que Laravel y el código esperan
            // Se asume que la FK es NOT NULL, si debe ser nullable, use ->nullable()
            $table->foreignId('contract_type_id')
                  ->after('fecha_fin') // Colocarla después de fecha_fin para que coincida con la posición del ENUM
                  ->constrained('contract_types'); // Asume que la tabla de tipos de contrato se llama 'contract_types'
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Revertir: Volver a agregar el ENUM si es necesario, pero esto es solo para reversa
            // Tendrías que definir los ENUMs exactos de nuevo. 
            // Para simplificar, solo revertimos la FK y no re-agregamos el ENUM (no recomendado en producción).
            $table->dropConstrainedForeignId('contract_type_id');
            // Nota: Si quiere ser perfecto, debe re-agregar la columna ENUM aquí.
        });
    }
};