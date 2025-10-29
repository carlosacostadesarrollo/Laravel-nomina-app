<?php

namespace App\Http\Controllers;

use App\Models\Configuracion; // Importa el modelo
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Como solo habrá una fila de configuración, buscamos la primera
        $configuracion = Configuracion::first();

        // Si la configuración no existe, redirigimos a la página de creación
        if (!$configuracion) {
            return redirect()->route('configuracion.create');
        }
        
        // Si existe, mostramos el formulario de edición (o una vista de detalles)
        return view('configuracion.edit', compact('configuracion'));
    }

    /**
     * Show the form for creating a new resource. (Muestra el formulario para crear)
     */
     public function create()
    {
        // Solución Limpia (Recomendada): 
        // Busca el único registro de configuración o crea una nueva instancia vacía.
        // La vista de 'configuracion.create' siempre se carga.
        $configuracion = Configuracion::firstOrNew([]);
        
        // Retorna la vista del formulario, pasando el objeto de configuración.
        return view('configuracion.create', compact('configuracion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'nombre_empresa' => 'required|string|max:150',
            // Asegura que el NIT sea único en la tabla 'configuracions'
            'nit' => 'required|string|max:30|unique:configuracions,nit', 
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email_contacto' => 'nullable|email|max:255',
            
            'moneda_base' => 'required|string|max:5',
            'salario_minimo_legal' => 'required|numeric|min:0',
            'porcentaje_arl' => 'required|numeric|min:0|max:100',
            'porcentaje_salud_empresa' => 'required|numeric|min:0|max:100',
            'porcentaje_pension_empresa' => 'required|numeric|min:0|max:100',

            'fecha_inicio_periodo' => 'required|date',
            'dias_periodo_nomina' => 'required|in:15,30',
            // logo_path se manejará después si incluimos subida de archivos
        ]);

        // 2. Verificar que no exista otra configuración (doble seguridad)
        if (Configuracion::count() > 0) {
            return redirect()->route('configuracion.index')
                             ->with('error', 'La configuración general ya existe y no se puede duplicar.');
        }

        // 3. Crear el registro en la base de datos (Mass Assignment seguro)
        Configuracion::create($request->all());

        // 4. Redirigir al formulario de edición con un mensaje de éxito
         return redirect()->route('configuracion.create')
                         ->with('success', 'Configuración guardada exitosamente. El sistema está listo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
