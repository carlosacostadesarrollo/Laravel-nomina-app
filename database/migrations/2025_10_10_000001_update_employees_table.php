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
        // Usamos Schema::table para modificar la tabla existente
        Schema::table('employees', function (Blueprint $table) {
            
            // 1. Añadir la clave foránea 'job_title_id'
            // Esto es lo que faltaba y causaba el error 'Unknown column job_title_id'
            // La columna se añade antes de 'salario_base'
            $table->foreignId('job_title_id')
                  ->after('email') // Posicionamiento ideal, después del email
                  ->constrained('job_titles') // Enlaza a la tabla 'job_titles'
                  ->onDelete('restrict'); // Evita borrar un cargo si hay empleados asociados
            
            // 2. Eliminar la columna 'cargo' obsoleta
            // No la necesitamos si usamos la clave foránea
            $table->dropColumn('cargo');

            // 3. Verificar que 'foto_path' y 'salario_base' existen (ya estaban en tu migración original)
            // Si no estaban, también se añadirían aquí.
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios en orden inverso:
        Schema::table('employees', function (Blueprint $table) {
            
            // 1. Recrear la columna 'cargo' (si fuera necesario para revertir)
            $table->string('cargo')->nullable()->after('email');
            
            // 2. Eliminar la clave foránea
            $table->dropForeign(['job_title_id']);
            $table->dropColumn('job_title_id');
        });
    }
};