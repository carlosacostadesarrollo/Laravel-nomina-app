<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CesantiasFund; // ¡Asegúrate de que este modelo exista y esté importado!

class CesantiasFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funds = [
            // Es vital incluir el NIT legal de cada entidad.
            // (Reemplazar con los NITs reales si es necesario)
            ['nombre' => 'PROTECCIÓN S.A.', 'nit' => '800224827'],
            ['nombre' => 'PORVENIR S.A.', 'nit' => '800231967'],
            ['nombre' => 'COLFONDOS S.A.', 'nit' => '800149496'],
            ['nombre' => 'FONDO NACIONAL DEL AHORRO (FNA)', 'nit' => '899999284'], // Fondo público para Cesantías
            ['nombre' => 'SKANDIA', 'nit' => '800148514'],
        ];

        foreach ($funds as $fund) {
            // Usa el NIT como criterio único para evitar duplicados y crear el registro
            CesantiasFund::firstOrCreate(['nit' => $fund['nit']], $fund);
        }
    }
}
