<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompensationFund;

class CompensationFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funds = [
            // NOTA: Reemplazar los NITs con los valores legales reales si es necesario.
            ['nombre' => 'COMPENSAR', 'nit' => '860066942'],
            ['nombre' => 'CAFAM', 'nit' => '860013570'],
            ['nombre' => 'COLSUBSIDIO', 'nit' => '860007336'],
            ['nombre' => 'COMFANDI', 'nit' => '890303208'],
            ['nombre' => 'COMFENALCO ANTIOQUIA', 'nit' => '890900842'],
            ['nombre' => 'CAFABA', 'nit' => '890270275'], // Caja de Barrancabermeja (ejemplo local)
        ];

        foreach ($funds as $fund) {
            // Usa el NIT como criterio único para evitar duplicados y crear el registro
            CompensationFund::firstOrCreate(['nit' => $fund['nit']], $fund);
        }
    }
}
