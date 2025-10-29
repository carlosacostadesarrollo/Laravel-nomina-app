<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContractType;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Tipos de Contrato Laboral más comunes en Colombia
            ['nombre' => 'INDEFINIDO'],
            ['nombre' => 'FIJO'],
            ['nombre' => 'OBRA O LABOR'],
            ['nombre' => 'APRENDIZAJE'],
            ['nombre' => 'PRACTICAS O CESANTIAS'],
            ['nombre' => 'TRANSITORIO'],
            ['nombre' => 'OCASIONAL, ACCIDENTAL O TRANSITORIO'],
        ];

        foreach ($types as $type) {
            // Usa el nombre como criterio único para evitar duplicados
            ContractType::firstOrCreate(['nombre' => $type['nombre']], $type);
        }
    }
}
