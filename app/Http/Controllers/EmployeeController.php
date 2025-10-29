<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ¡Necesario para las fotos!
use App\Models\JobTitle;


class EmployeeController extends Controller
{
    /**
     * Mostrar listado de empleados.
     */
    public function index()
    {
        $employees = Employee::orderBy('nombre')->get();
        return view('employees.index', compact('employees'));
    }

    public function searchByCedula(Request $request)
    {
        $cedula = $request->input('cedula');

        // La consulta trae los campos necesarios directamente de la tabla employees
        $employee = Employee::where('identificacion', $cedula) 
            ->select('id', 'nombre', 'apellido', 'job_title_id', 'salario_base') // <-- Agregamos los campos de cargo y salario
            ->first();

        if ($employee) {
            return response()->json([
                'found' => true,
                'employee' => $employee
            ]);
        }
        
        return response()->json(['found' => false], 404);
    }
    /**
     * Mostrar el formulario para crear un nuevo empleado.
     */
    public function create()
    {

        // 1. Obtiene los cargos
        $jobTitles = JobTitle::orderBy('nombre')->get(); 
        
        // 2. Pasa la lista a la vista
        return view('employees.create', compact('jobTitles')); 
    }   
    /**
     * Almacena un nuevo empleado en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de Datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'identificacion' => 'required|string|max:20|unique:employees,identificacion',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:F,M,O',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            
            // Campo clave: el ID del cargo debe existir en la tabla job_titles
            'job_title_id' => 'required|exists:job_titles,id', 
            
            // El salario debe ser numérico y al menos el mínimo legal (o superior a 0)
            'salario_base' => 'required|numeric|min:1', 
            
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'direccion' => 'nullable|string|max:255',
            
            // La foto es opcional, pero si existe, debe ser una imagen
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB máximo
        ]);

        // 2. Manejo de la Subida de la Foto
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/empleados_fotos');
            // Almacenamos solo el nombre del archivo o la ruta relativa
            $validatedData['foto_path'] = str_replace('public/', '', $path); 
        }

        // 3. Guardado del Empleado
        Employee::create($validatedData);

        // 4. Redirección con Mensaje de Éxito
        return redirect()->route('employees.index')
                         ->with('success', '¡Empleado ' . $validatedData['nombre'] . ' ' . $validatedData['apellido'] . ' creado exitosamente!');
    }

    /**
     * Mostrar el formulario para editar el empleado especificado.
     */
    public function edit(Employee $employee)
    {
        // Pasa el objeto empleado a la vista edit.blade.php
        $jobTitles = JobTitle::orderBy('nombre')->get(); 
        return view('employees.edit', compact('employee', 'jobTitles'));
    }

    /**
     * Actualizar el empleado especificado en la base de datos.
     */
    public function update(Request $request, Employee $employee)
    {
        // 1. Validación de datos (Asegurarse de ignorar al empleado actual)
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            // El 'unique' ignora el ID del empleado que estamos editando
            'identificacion' => 'required|string|max:20|unique:employees,identificacion,' . $employee->id,
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F,O',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            
            'cargo' => 'required|string|max:100',
            'salario_base' => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
        ]);

        // 2. Manejo de la subida de la foto (si se sube una nueva)
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($employee->foto_path) {
                Storage::delete('public/' . $employee->foto_path);
            }
            
            // Guardar la nueva foto
            $path = $request->file('foto')->store('public/employees');
            $validatedData['foto_path'] = str_replace('public/', '', $path);
        }

        // 3. Actualizar el empleado en la base de datos
        $employee->update($validatedData);

        // 4. Redirección
        return redirect()->route('employees.index')
                         ->with('success', '¡Empleado actualizado exitosamente!');
    }

    /**
     * Eliminar el empleado especificado de la base de datos y su foto.
     */
    public function destroy(Employee $employee)
    {
        // 1. Eliminar la foto del disco si existe
        if ($employee->foto_path) {
            Storage::delete('public/' . $employee->foto_path);
        }

        // 2. Eliminar el registro de la base de datos
        $employee->delete();

        // 3. Redirección
        return redirect()->route('employees.index')
                         ->with('success', '¡Empleado eliminado correctamente!');
    }
}