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
        // La tabla que guarda las 10 entidades ARL
        Schema::create('arl_entities', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nit')->unique(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arl_entities');
    }
};