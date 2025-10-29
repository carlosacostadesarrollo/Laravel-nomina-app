<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArlEntity; // ¡Modelo ArlEntity importado!

class ArlEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arlEntities = [
            ['nombre' => 'POSITIVA', 'nit' => '860010993-8'],
            ['nombre' => 'ARL SURA', 'nit' => '890900609-0'],
            ['nombre' => 'AXA COLPATRIA', 'nit' => '860007204-1'],
            ['nombre' => 'COMPAÑIA DE SEGUROS BOLIVAR', 'nit' => '860002166-4'],
            ['nombre' => 'LA EQUIDAD SEGUROS', 'nit' => '860005118-2'],
            ['nombre' => 'ALIANZA', 'nit' => '800202680-6'],
            ['nombre' => 'MAPFRE', 'nit' => '860006739-1'],
            ['nombre' => 'SEGUROS DE VIDA AURORA', 'nit' => '800249704-1'],
            ['nombre' => 'SEGUROS DE VIDA DEL ESTADO', 'nit' => '860008544-6'],
            ['nombre' => 'PROTECCIÓN SEGUROS DE VIDA', 'nit' => '860009565-5'],
        ];

        foreach ($arlEntities as $arl) {
            // Usa el NIT como criterio único para evitar duplicados
            ArlEntity::firstOrCreate(['nit' => $arl['nit']], $arl);
        }
    }
}
