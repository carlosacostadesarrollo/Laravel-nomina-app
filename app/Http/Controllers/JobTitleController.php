<?php

// app/Http/Controllers/JobTitleController.php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class JobTitleController extends Controller
{
    /**
     * Muestra una lista de todos los cargos.
     */
    public function index()
    {
        $jobTitles = JobTitle::orderBy('nombre')->get();
        return view('job_titles.index', compact('jobTitles'));
    }

    /**
     * Muestra el formulario para crear un nuevo cargo.
     */
    public function create()
    {
        return view('job_titles.create');
    }

    /**
     * Almacena un nuevo cargo en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100|unique:job_titles,nombre',
            'salario_base' => 'required|numeric|min:0',
        ]);

        JobTitle::create($validatedData);

        return redirect()->route('job_titles.index')->with('success', '¡Cargo ' . $validatedData['nombre'] . ' creado exitosamente!');
    }

    /**
     * Muestra el formulario para editar un cargo existente.
     */
    public function edit(JobTitle $jobTitle)
    {
        return view('job_titles.edit', compact('jobTitle'));
    }

    /**
     * Actualiza un cargo existente en la base de datos.
     */
    public function update(Request $request, JobTitle $jobTitle)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100|unique:job_titles,nombre,' . $jobTitle->id,
            'salario_base' => 'required|numeric|min:0',
        ]);

        $jobTitle->update($validatedData);

        return redirect()->route('job_titles.index')->with('success', '¡Cargo ' . $validatedData['nombre'] . ' actualizado exitosamente!');
    }

    /**
     * Elimina un cargo de la base de datos.
     */
    public function destroy(JobTitle $jobTitle)
    {
     try {   // En un sistema real, deberías verificar que este cargo no esté asignado a empleados antes de eliminar.
        $jobTitle->delete();

        return redirect()->route('job_titles.index')->with('success', '¡Cargo eliminado exitosamente!');
     }catch (QueryException $e) {
        // El código 23000 es la violación de integridad de clave foránea (Foreign Key Constraint)
        if ($e->getCode() == 23000) {
            return redirect()->route('job_titles.index')->with('error', 
                'No se puede eliminar el cargo "' . $jobTitle->nombre . '". Hay empleados asociados a este cargo.'
            );
        }
     }
    }
}