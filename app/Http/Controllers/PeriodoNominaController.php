<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodoNomina; // <--- 1. IMPORTAR el Modelo
use Illuminate\Database\QueryException;

class PeriodoNominaController extends Controller
{
    /**
     * Muestra la lista de períodos de nómina.
     */
    public function index()
    {
        // 2. OBTENER los períodos de la base de datos para la vista de listado
        $periodos = PeriodoNomina::orderBy('anio', 'desc')->orderBy('mes', 'desc')->get();
        
        return view('periodos_nomina.index', [
            'header' => 'Gestión de Períodos de Nómina',
            'periodos' => $periodos, // Pasamos los datos a la vista
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo período.
     */
    public function create()
    {
        return view('periodos_nomina.create', [
            'header' => 'Crear Período de Nómina'
        ]);
    }

    /**
     * Almacena un nuevo período de nómina en la base de datos y valida la duración.
     */
    public function store(Request $request)
    {
        // 3. VALIDACIÓN BÁSICA (Añadidos 'anio' y 'mes')
        $validatedData = $request->validate([
            'anio' => 'required|integer|min:' . (date('Y') - 1), // Asegura un año razonable
            'mes' => 'required|string|size:2', // Mes como string de dos dígitos (ej: '01')
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:Abierto,Cerrado,Pagado',
        ]);

        // 4. OBTENER DÍAS DE CONFIGURACIÓN
        // ASUMIMOS que la tabla de configuración se llama 'configuracion_general'
        $config = DB::table('configuracions')->where('id', 1)->first();
        
        if (!$config || !isset($config->dias_periodo_nomina)) {
            return redirect()->back()->withInput()->withErrors([
                'configuracion' => 'Error: No se pudo obtener la configuración de días de nómina (15 o 30 días). Verifique la tabla `configuracion_general`.'
            ]);
        }
        
        $dias_configuracion = (int) $config->dias_periodo_nomina;
        
        // 5. COMPARAR el rango de fechas con la configuración
        
        $fechaInicio = Carbon::parse($validatedData['fecha_inicio']);
        $fechaFin = Carbon::parse($validatedData['fecha_fin']);
        
        // Calcular la diferencia total de días (diffInDays + 1 para incluir el día de inicio)
        $dias_seleccionados = $fechaInicio->diffInDays($fechaFin) + 1;

        // Tolerancia de 1 día (ej: 29 a 31 para mensual, 14 a 16 para quincenal)
        $tolerancia = 1; 

        $min_dias = $dias_configuracion - $tolerancia;
        $max_dias = $dias_configuracion + $tolerancia;
        
        // Verificar si la cantidad de días está fuera del rango permitido
        if ($dias_seleccionados < $min_dias || $dias_seleccionados > $max_dias) {
            
            $tipo_nomina = ($dias_configuracion === 15) ? 'Quincenal (15 días)' : 'Mensual (30 días)';

            return redirect()->back()->withInput()->withErrors([
                'fecha_fin' => "El rango de fechas seleccionado es de **{$dias_seleccionados} días**. La configuración actual es **{$tipo_nomina}**, la cual requiere un período de {$dias_configuracion} días (rango aceptado: {$min_dias} a {$max_dias} días)."
            ]);
        }
        
        // 6. CREACIÓN DEL PERÍODO (Si pasa la validación)
        PeriodoNomina::create($validatedData);
        
        return redirect()->route('periodos_nomina.index')
                         ->with('success', '✅ Período de nómina creado y validado exitosamente!');
    }

    /**
     * Otros métodos CRUD (deben estar vacíos o con un esqueleto)
     */
    public function show(string $id) 
    {
        $periodo = PeriodoNomina::findOrFail($id);
        return view('periodos_nomina.show', compact('periodo'));
    }

    public function edit(string $id) 
    {
        $periodo = PeriodoNomina::findOrFail($id);
        return view('periodos_nomina.edit', compact('periodo'));
    }

        public function update(Request $request, string $id)
         {
                // 1. VALIDACIÓN
                // Se valida que todos los campos requeridos estén presentes y en el formato correcto.
                $validatedData = $request->validate([
                    'anio' => ['required', 'string', 'max:4'],
                    'mes' => ['required', 'string', 'max:2'],
                    'fecha_inicio' => ['required', 'date'],
                    'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
                    'estado' => ['required', 'string', 'in:Abierto,Cerrado,Pagado'], // Basado en tu DB
                ], [
                    'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
                ]);

                // 2. ENCONTRAR Y ACTUALIZAR
                try {
                    // Busca el período de nómina por ID o lanza una excepción 404
                    $periodo = PeriodoNomina::findOrFail($id);
                    
                    // Actualiza el registro con los datos validados (funciona porque 'estado' está en $fillable)
                    $periodo->update($validatedData);

                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    // Redirige si el registro no se encuentra
                    return redirect()->route('periodos_nomina.index')
                                    ->with('error', 'El período de nómina no pudo ser encontrado para su actualización.');
                }

                // 3. REDIRECCIÓN
                return redirect()->route('periodos_nomina.index')
                                ->with('success', 'El período de nómina fue actualizado exitosamente.');
          }

    public function destroy(string $id)
       {
            try {
                // 1. Encontrar el período de nómina por ID
                $periodo = PeriodoNomina::findOrFail($id);

                // 2. Intentar eliminar el registro. Si existen dependencias (Foreign Keys), 
                // esta línea lanzará una QueryException.
                $periodo->delete();

                // 3. Si la eliminación es exitosa, redirige con mensaje de éxito
                return redirect()->route('periodos_nomina.index')
                                ->with('success', 'El período de nómina fue eliminado exitosamente.');

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                // Captura si el registro no existe
                return redirect()->route('periodos_nomina.index')
                                ->with('error', 'El período de nómina que intentas eliminar no se encontró.');
                                
            } catch (QueryException $e) {
                // 4. Captura el error de restricción de clave foránea
                // y notifica al usuario que no puede eliminar el registro debido a sus dependencias.
                return redirect()->route('periodos_nomina.index')
                                ->with('error', 'No se puede eliminar este período porque tiene registros asociados (ej. nóminas o pagos). Elimine los registros dependientes primero.');
            }
       }
}