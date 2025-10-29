<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PensionFund;

class PensionFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funds = [
            // Es vital incluir el NIT legal de cada entidad.
            // (Reemplazar con los NITs reales si es necesario)
            ['nombre' => 'PROTECCIÓN S.A.', 'nit' => '800229739'],
            ['nombre' => 'PORVENIR S.A.', 'nit' => '800224808'],
            ['nombre' => 'COLFONDOS S.A.', 'nit' => '800227940'],
            ['nombre' => 'COLPENSIONES', 'nit' => '900336004'], // Administrador Público
            ['nombre' => 'SKANDIA', 'nit' => '800148514']
        ];

        foreach ($funds as $fund) {
            // Usa el NIT como criterio único para evitar duplicados y crear el registro
            PensionFund::firstOrCreate(['nit' => $fund['nit']], $fund);
        }
    }
}
