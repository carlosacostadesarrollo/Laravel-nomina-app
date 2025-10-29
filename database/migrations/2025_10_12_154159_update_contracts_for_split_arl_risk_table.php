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
        Schema::table('contracts', function (Blueprint $table) {
            
            // 1. Eliminar la columna antigua (arl_id) y su FK de forma SEGURA.
            // Usamos una verificación condicional para evitar el error "Can't DROP FOREIGN KEY" 
            // en el migrate:fresh si la columna nunca fue creada.
            if (Schema::hasColumn('contracts', 'arl_id')) {
                // El método dropConstrainedForeignId() elimina la FK y la columna en Laravel.
                $table->dropConstrainedForeignId('arl_id');
            }

            // 2. Añadir las dos nuevas claves foráneas (arl_entity_id y risk_level_id).
            // Estas apuntan a las nuevas tablas que contienen las listas cortas.
            
            $table->foreignId('arl_entity_id')
                  ->constrained('arl_entities') // Apunta a la tabla de las 10 entidades
                  ->onDelete('restrict')
                  ->after('eps_id');
                  
            $table->foreignId('risk_level_id')
                  ->constrained('risk_levels')  // Apunta a la tabla de los 5 niveles de riesgo
                  ->onDelete('restrict')
                  ->after('arl_entity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            
            // 1. Revertir: Eliminar las nuevas columnas
            $table->dropForeign(['arl_entity_id']);
            $table->dropColumn('arl_entity_id');
            
            $table->dropForeign(['risk_level_id']);
            $table->dropColumn('risk_level_id');
            
            // 2. Recrear la columna antigua (solo si se necesita un rollback limpio)
            // Ya que hemos cambiado el esquema, este paso es peligroso.
            // Para mantener la consistencia, la dejamos comentada o se podría añadir 
            // la columna arl_id sin FKs si existiera una tabla 'arls' de respaldo.
            // $table->foreignId('arl_id')->nullable()->constrained('arls')->onDelete('restrict')->after('eps_id');
        });
    }
};