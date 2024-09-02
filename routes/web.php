<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/send-test-email', function () {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('adonay202024@gmail.com')
                ->subject('Test Email');
    });

    return 'Test email sent successfully!';
});


Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//primero creamos un controlador php artisa make:controller AdminController
//rutas para el administrador
//http://localhost/GYMU/siswebgym/public/admin
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');
Route::get('/admin/home', [App\Http\Controllers\AdminController::class, 'home'])->name('admin.home')->middleware('auth');

// Rutas para el perfil del usuario autenticado
Route::get('/profile/show', [App\Http\Controllers\UsuarioController::class, 'profile'])->name('profile.index')->middleware('auth');
Route::put('/profile/update', [App\Http\Controllers\UsuarioController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

//Route::middleware(['auth'])->group(function () {
 //   Route::get('/admin/empleados', [App\Http\Controllers\EmpleadoController::class, 'index'])->name('admin.empleados.index');
 //   Route::get('/admin/empleados/create', [App\Http\Controllers\EmpleadoController::class, 'create'])->name('admin.empleados.create');
 //   Route::post('/admin/empleados', [App\Http\Controllers\EmpleadoController::class, 'store'])->name('admin.empleados.store');
 //   Route::get('/admin/empleados/{id}/edit', [App\Http\Controllers\EmpleadoController::class, 'edit'])->name('admin.empleados.edit');
 //   Route::put('/admin/empleados/{id}', [App\Http\Controllers\EmpleadoController::class, 'update'])->name('admin.empleados.update');
 //   Route::delete('/admin/empleados/{id}', [App\Http\Controllers\EmpleadoController::class, 'destroy'])->name('admin.empleados.destroy');
 //   Route::get('/admin/empleados/profile', [App\Http\Controllers\EmpleadoController::class, 'profile'])->name('admin.empleados.profile');
 //   Route::put('/admin/empleados/profile/update', [App\Http\Controllers\EmpleadoController::class, 'updateProfile'])->name('admin.empleados.updateProfile');
 //   
//});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/entrenadores', [App\Http\Controllers\EntrenadorController::class, 'index'])->name('admin.entrenadores.index');
    Route::get('/admin/entrenadores/create', [App\Http\Controllers\EntrenadorController::class, 'create'])->name('admin.entrenadores.create');
    Route::post('/admin/entrenadores', [App\Http\Controllers\EntrenadorController::class, 'store'])->name('admin.entrenadores.store');
    Route::get('/admin/entrenadores/{id}/edit', [App\Http\Controllers\EntrenadorController::class, 'edit'])->name('admin.entrenadores.edit');
    Route::put('/admin/entrenadores/{id}', [App\Http\Controllers\EntrenadorController::class, 'update'])->name('admin.entrenadores.update');
    Route::delete('/admin/entrenadores/{id}', [App\Http\Controllers\EntrenadorController::class, 'destroy'])->name('admin.entrenadores.destroy');
    Route::get('/admin/entrenadores/profile', [App\Http\Controllers\EntrenadorController::class, 'profile'])->name('admin.entrenadores.profile');
    Route::put('/admin/entrenadores/profile/update', [App\Http\Controllers\EntrenadorController::class, 'updateProfile'])->name('admin.entrenadores.updateProfile');
   // Route::get('/admin/entrenadores/{id}/show', [App\Http\Controllers\EntrenadorController::class, 'show'])->name('admin.entrenadores.show');
    Route::delete('/admin/entrenadores/{id}/force', [App\Http\Controllers\EntrenadorController::class, 'forceDestroy'])->name('admin.entrenadores.forceDestroy');
    Route::patch('/admin/entrenadores/{id}/restore', [App\Http\Controllers\EntrenadorController::class, 'restore'])->name('admin.entrenadores.restore');
    Route::get('/admin/entrenadores/eliminados', [App\Http\Controllers\EntrenadorController::class, 'eliminados'])->name('admin.entrenadores.eliminados');
    Route::get('admin/entrenadores/pdf', [App\Http\Controllers\EntrenadorController::class, 'exportPDF'])->name('admin.entrenadores.pdf');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index');
    Route::get('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create');
    Route::post('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store');
    Route::get('/admin/clientes/{id}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit');
    Route::put('/admin/clientes/{id}', [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update');
    Route::delete('/admin/clientes/{id}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy');
    Route::get('/admin/clientes/profile', [App\Http\Controllers\ClienteController::class, 'profile'])->name('admin.clientes.profile');
    Route::put('/admin/clientes/profile/update', [App\Http\Controllers\ClienteController::class, 'updateProfile'])->name('admin.clientes.updateProfile');
    Route::delete('/admin/clientes/{id}/force', [App\Http\Controllers\ClienteController::class, 'forceDestroy'])->name('admin.clientes.forceDestroy');
    Route::patch('/admin/clientes/{id}/restore', [App\Http\Controllers\ClienteController::class, 'restore'])->name('admin.clientes.restore');
    Route::get('/admin/clientes/eliminados', [App\Http\Controllers\ClienteController::class, 'eliminados'])->name('admin.clientes.eliminados');
    Route::get('admin/clientes/pdf', [App\Http\Controllers\ClienteController::class, 'exportPDF'])->name('admin.clientes.pdf');



});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/planes', [App\Http\Controllers\PlanMembresiaController::class, 'index'])->name('admin.planes.index');
    Route::post('/admin/planes', [App\Http\Controllers\PlanMembresiaController::class, 'store'])->name('admin.planes.store');
    Route::get('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'show'])->name('admin.planes.show');
    Route::put('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'update'])->name('admin.planes.update');
    Route::delete('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'destroy'])->name('admin.planes.destroy');

    //modulo de inscripciones 
    Route::get('/admin/inscripciones/crear', [App\Http\Controllers\InscripcionController::class, 'create'])->name('admin.inscripciones.create');

    // Ruta para almacenar la inscripción y el pago
    Route::post('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store');

    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/horarios', [App\Http\Controllers\HorarioController::class, 'index'])->name('admin.horarios.index');
        //Route::get('/admin/planes/create', [App\Http\Controllers\PlanMembresiaController::class, 'create'])->name('admin.planes.create');
        //Route::post('/admin/planes', [App\Http\Controllers\PlanMembresiaController::class, 'store'])->name('admin.planes.store');
        //Route::get('/admin/planes/{id}/edit', [App\Http\Controllers\PlanMembresiaController::class, 'edit'])->name('admin.planes.edit');
        //Route::put('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'update'])->name('admin.planes.update');
        //Route::delete('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'destroy'])->name('admin.planes.destroy');
        
    
        //asistencias
        Route::get('/admin/asistencias', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('admin.asistencias.index');
        Route::get('/admin/asistencias/registrar-cliente', [App\Http\Controllers\AsistenciaController::class, 'registro'])->name('admin.asistencias.cliente');
        Route::post('/admin/asistencias/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrar'])->name('admin.asistencias.registrar');
        //Route::post('/admin/asistencias/registrar-salida', [App\Http\Controllers\AsistenciaController::class, 'registrarSalida'])->name('admin.asistencias.registrar-salida');
        Route::get('/admin/asistencias/estadisticas', [App\Http\Controllers\AsistenciaController::class, 'estadisticas'])->name('admin.asistencias.estadisticas');
        route::get('/admin/asistencias/{id}/edit', [App\Http\Controllers\AsistenciaController::class, 'edit'])->name('admin.asistencias.edit');
        Route::put('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'update'])->name('admin.asistencias.update');
        Route::delete('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'destroy'])->name('admin.asistencias.destroy');
        Route::get('/admin/autocomplete-clientes', [App\Http\Controllers\AsistenciaController::class, 'autocompleteClientes'])->name('admin.autocomplete.clientes');
    });


        Route::middleware(['auth'])->group(function () {
            Route::get('/cliente/asistencia', [App\Http\Controllers\AsistenciaController::class, 'show'])->name('cliente.asistencias.asistencia');
            Route::post('/cliente/asistencia/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrar'])->name('cliente.asistencias.registrar-asistencia');
            Route::get('/cliente/asistencia/reporte', [App\Http\Controllers\AsistenciaController::class, 'reporte'])->name('cliente.asistencias.reporte-asistencia');
            Route::put('/cliente/asistencia/{asistencia}', [App\Http\Controllers\AsistenciaController::class, 'corregirAsistencia'])->name('cliente.asistencias.corregir-asistencia');
            Route::delete('/cliente/asistencia/{asistencia}', [App\Http\Controllers\AsistenciaController::class, 'eliminarAsistencia'])->name('cliente.asistencias.eliminar-asistencia');
        });



    


    //configuracion del envio de email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
    
        return redirect()->route('password.change');
    })->middleware(['auth', 'signed'])->name('verification.verify');


    Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'showChangeForm'])->name('password.change');
Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'changePassword'])->name('password.change.submit');
