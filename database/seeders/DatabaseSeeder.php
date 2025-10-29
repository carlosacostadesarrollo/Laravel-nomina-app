<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear usuario de prueba
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@nomina.com',
            'password' => bcrypt('password'), // Se recomienda usar una contraseña segura
        ]);

        // 2. Ejecutar Seeders de Tablas de Referencia
        // Estas tablas DEBEN estar llenas para que el formulario de Contratos funcione.
        $this->call([
            // Estructura y Clasificación
            ContractTypeSeeder::class,
            // JobTitleSeeder::class, // Asumiendo que tienes una lista de cargos
            
            // Entidades de Seguridad Social (Necesitan el NIT)
            EpsSeeder::class,
            PensionFundSeeder::class,
            ArlEntitySeeder::class,  // Nueva entidad ARL
            RiskLevelSeeder::class,   // Los 5 niveles de riesgo
            CompensationFundSeeder::class,
            CesantiasFundSeeder::class,
            
            // Datos de prueba (si es necesario que existan empleados para probar el contrato)
            // EmployeeSeeder::class, 
        ]);
    }
}
