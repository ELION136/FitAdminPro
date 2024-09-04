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
    Route::post('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store');
    Route::get('/membresias/historial', [App\Http\Controllers\MembresiaController::class, 'historial'])->name('admin.membresias.historial');
    Route::get('/admin/membresias/reporte', [App\Http\Controllers\MembresiaController::class, 'generarPDF'])->name('admin.membresias.generarPDF');
    Route::get('/admin/membresias/credencial/{id}', [App\Http\Controllers\MembresiaController::class, 'generarCredencial'])->name('admin.membresias.generarCredencial');

    Route::get('/admin/pagos', [App\Http\Controllers\PagoController::class, 'index'])->name('admin.pagos.index');
    Route::get('/admin/pagos/reporte', [App\Http\Controllers\PagoController::class, 'generarReporte'])->name('admin.pagos.reporte');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/admin/horarios', [App\Http\Controllers\HorarioController::class, 'index'])->name('admin.horarios.index');
    Route::post('/admin/horarios/asignar', [App\Http\Controllers\HorarioController::class, 'asignarServicio'])->name('admin.horarios.asignarServicio');
    Route::get('/admin/horarios/{idEntrenador}', [App\Http\Controllers\HorarioController::class, 'getHorarios'])->name('admin.horarios.getHorarios');
    
    Route::get('/admin/horarios2', [App\Http\Controllers\Horario2Controller::class, 'index'])->name('admin.horarios2.index');
    Route::put('/admin/horarios2/{idHorario}', [App\Http\Controllers\Horario2Controller::class, 'update'])->name('admin.horarios2.update');
    Route::delete('/admin/horarios2/{idHorario}', [App\Http\Controllers\Horario2Controller::class, 'destroy'])->name('admin.horarios2.destroy');






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


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/servicios', [App\Http\Controllers\ServiciosController::class, 'index'])->name('admin.servicios.index');
    Route::post('/admin/servicios', [App\Http\Controllers\ServiciosController::class, 'store'])->name('admin.servicios.store');
    Route::get('/admin/servicios/{servicio}/edit', [App\Http\Controllers\ServiciosController::class, 'edit'])->name('admin.servicios.edit');
    Route::put('/admin/servicios/{servicio}', [App\Http\Controllers\ServiciosController::class, 'update'])->name('admin.servicios.update');
    Route::delete('/admin/servicios/{servicio}', [App\Http\Controllers\ServiciosController::class, 'destroy'])->name('admin.servicios.destroy');
});



//configuracion del envio de email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('password.change');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'showChangeForm'])->name('password.change');
Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'changePassword'])->name('password.change.submit');
