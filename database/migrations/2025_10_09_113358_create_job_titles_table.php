<?php

// database/migrations/YYYY_MM_DD_create_job_titles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_titles', function (Blueprint $table) {
            $table->id();
            // Nombre del cargo (debe ser único)
            $table->string('nombre', 100)->unique(); 
            // Salario base sugerido para el cargo
            $table->decimal('salario_base', 10, 2)->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_titles');
    }
};