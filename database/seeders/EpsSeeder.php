<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Eps; // Importamos el modelo Eps

class EpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $epsEntities = [
            // Es vital incluir el NIT legal de cada entidad
            ['nombre' => 'EPS SURA', 'nit' => '800088702'],
            ['nombre' => 'COMPENSAR', 'nit' => '860066942'],
            ['nombre' => 'NUEVA EPS', 'nit' => '900156264'],
            ['nombre' => 'SALUD TOTAL S.A.', 'nit' => '800130907'],
            ['nombre' => 'EPS SANITAS', 'nit' => '800251440'],
            ['nombre' => 'COOMEVA EPS', 'nit' => '805000427'],
        ];

        // -----------------------------------------------------
        // LÓGICA DE INSERCIÓN Y VALIDACIÓN DE DUPLICADOS (AÑADIDA)
        // -----------------------------------------------------
        foreach ($epsEntities as $eps) {
            
            // firstOrCreate: Busca un registro por el NIT. 
            // Si lo encuentra, no hace nada; si no lo encuentra, lo crea.
            Eps::firstOrCreate(
                ['nit' => $eps['nit']], // Criterio de búsqueda (clave única)
                $eps                    // Datos a insertar si no existe
            );
        }
    }
}