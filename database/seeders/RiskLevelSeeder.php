<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RiskLevel;

class RiskLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
        $riskRates = [
            ['nombre' => 'Clase I (Mínimo)',   'porcentaje' => 0.00522],
            ['nombre' => 'Clase II (Bajo)',    'porcentaje' => 0.01044],
            ['nombre' => 'Clase III (Medio)',  'porcentaje' => 0.02436],
            ['nombre' => 'Clase IV (Alto)',    'porcentaje' => 0.04350],
            ['nombre' => 'Clase V (Máximo)',   'porcentaje' => 0.06960],
        ];
        
        foreach ($riskRates as $rate) {
            RiskLevel::firstOrCreate(['nombre' => $rate['nombre']], $rate);
        }
    }
    }
}
