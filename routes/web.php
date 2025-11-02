<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PeriodoNominaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
   
    // 1. Definición del recurso de Empleados (se mantiene igual)
    Route::resource('employees', EmployeeController::class)->except(['show']); 

    //Ruta AJAX para búsqueda de Cédula (usa un query string ?cedula=...)
    Route::get('employees/search', [App\Http\Controllers\EmployeeController::class, 'searchByCedula'])->name('employees.search');

    // Rutas de Configuración
    Route::get('/configuracion/create', [ConfiguracionController::class, 'create'])->name('configuracion.create');
    Route::post('/configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store');
    
     // Esto habilita las rutas: index, create, store, show, edit, y update.
      Route::resource('contracts', ContractController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    // Rutas de Cargos (Job Titles) - Excluir 'show' si no está implementado
    Route::resource('job_titles', JobTitleController::class)->except(['show']);

    Route::resource('contracts', ContractController::class);

    //----------------------------------------------------
    // 1. Período Nómina 
    // ----------------------------------------------------
     Route::resource('periodos_nomina', PeriodoNominaController::class)->names('periodos_nomina');
    
    // 2. Empresas para Embargos
    Route::get('/embargos', function () { return view('placeholder.embargos_index'); })->name('embargos.index');
    
    // 3. Registrar Dependientes
    Route::get('/dependientes', function () { return view('placeholder.dependientes_index'); })->name('dependientes.index');

    // Módulos Futuros (Rutas placeholder)
    Route::get('/nomina', function () { return view('nomina.index'); })->name('nomina.index');
    Route::get('/novedades', function () { return view('novedades.index'); })->name('novedades.index');
    Route::get('/reports', function () { return view('reports.index'); })->name('reports.index');
});

require __DIR__.'/auth.php';
