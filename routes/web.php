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



Route::get('/', function () {
    return view('index');
});

Auth::routes();


Route::post('/custom-password-reset', [App\Http\Controllers\CustomPasswordResetController::class, 'sendResetLink'])->name('password.custom_reset');
Route::get('/custom-reset-password', function () {
    return view('custom_reset_password');
})->name('password.custom_reset_form');
Route::post('/custom-reset-password', [App\Http\Controllers\CustomPasswordResetController::class, 'resetPassword'])->name('password.custom_update');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//primero creamos un controlador php artisa make:controller AdminController modificar esta ruta para ruta
//rutas para el administrador
//http://localhost/GYMU/siswebgym/public/admin
// Rutas para restablecer contraseña
Auth::routes(['verify' => true]);

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');
Route::get('/admin/home', [App\Http\Controllers\AdminController::class, 'home'])->name('admin.home')->middleware('auth');

// Rutas para el perfil del usuario autenticado
Route::get('/profile/show', [App\Http\Controllers\UsuarioController::class, 'profile'])->name('profile.index')->middleware('auth');
Route::put('/profile/update', [App\Http\Controllers\UsuarioController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
route::post('/profile/validate', [App\Http\Controllers\UsuarioController::class, 'validateProfile'])->name('profile.validate')->middleware('auth');







Route::middleware(['auth'])->group(function () {
    Route::get('/admin/entrenadores', [App\Http\Controllers\EntrenadorController::class, 'index'])->name('admin.entrenadores.index');
    Route::get('/admin/entrenadores/create', [App\Http\Controllers\EntrenadorController::class, 'create'])->name('admin.entrenadores.create');
    Route::post('/admin/entrenadores', [App\Http\Controllers\EntrenadorController::class, 'store'])->name('admin.entrenadores.store');
    Route::get('/admin/entrenadores/{id}/edit', [App\Http\Controllers\EntrenadorController::class, 'edit'])->name('admin.entrenadores.edit');
    Route::put('/admin/entrenadores/{id}', [App\Http\Controllers\EntrenadorController::class, 'update'])->name('admin.entrenadores.update');
    Route::delete('/admin/entrenadores/{id}', [App\Http\Controllers\EntrenadorController::class, 'destroy'])->name('admin.entrenadores.destroy');
    //Route::get('/admin/entrenadores/profile', [App\Http\Controllers\EntrenadorController::class, 'profile'])->name('admin.entrenadores.profile');
    //Route::put('/admin/entrenadores/profile/update', [App\Http\Controllers\EntrenadorController::class, 'updateProfile'])->name('admin.entrenadores.updateProfile');
    // Route::get('/admin/entrenadores/{id}/show', [App\Http\Controllers\EntrenadorController::class, 'show'])->name('admin.entrenadores.show');
    //Route::delete('/admin/entrenadores/{id}/force', [App\Http\Controllers\EntrenadorController::class, 'forceDestroy'])->name('admin.entrenadores.forceDestroy');
    //Route::patch('/admin/entrenadores/{id}/restore', [App\Http\Controllers\EntrenadorController::class, 'restore'])->name('admin.entrenadores.restore');
    //Route::get('/admin/entrenadores/eliminados', [App\Http\Controllers\EntrenadorController::class, 'eliminados'])->name('admin.entrenadores.eliminados');
    //Route::get('admin/entrenadores/pdf', [App\Http\Controllers\EntrenadorController::class, 'exportPDF'])->name('admin.entrenadores.pdf');
    //Route::get('admin/entrenadores/export/excel', [App\Http\Controllers\EntrenadorController::class, 'exportExcel'])->name('admin.entrenadores.export.excel');
    Route::post('/admin/entrenadores/validateField', [App\Http\Controllers\EntrenadorController::class, 'validateField'])
        ->name('admin.entrenadores.validateField');


    Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::post('/admin/usuarios/store', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::put('/admin/usuarios/update/{idUsuario}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/destroy/{idUsuario}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::put('/admin/usuarios/toggleStatus/{idUsuario}', [App\Http\Controllers\UsuarioController::class, 'toggleStatus'])->name('admin.usuarios.toggleStatus');
    Route::post('/admin/usuarios/validate', [App\Http\Controllers\UsuarioController::class, 'validateField'])->name('admin.usuarios.validate');



    Route::get('/admin/membresias', [App\Http\Controllers\MembresiaController::class, 'index'])->name('admin.membresias.index');
    Route::post('/admin/membresias', [App\Http\Controllers\MembresiaController::class, 'store'])->name('admin.membresias.store');
    Route::put('/admin/membresias/{id}', [App\Http\Controllers\MembresiaController::class, 'update'])->name('admin.membresias.update');
    Route::delete('/admin/membresias/{id}', [App\Http\Controllers\MembresiaController::class, 'destroy'])->name('admin.membresias.destroy');

    Route::get('/admin/servicios', [App\Http\Controllers\ServiciosController::class, 'index'])->name('admin.servicios.index');
    Route::post('/admin/servicios', [App\Http\Controllers\ServiciosController::class, 'store'])->name('admin.servicios.store');
    Route::put('/admin/servicios/{id}', [App\Http\Controllers\ServiciosController::class, 'update'])->name('admin.servicios.update');
    Route::delete('/admin/servicios/{id}', [App\Http\Controllers\ServiciosController::class, 'destroy'])->name('admin.servicios.destroy');

    Route::get('/admin/categorias', [App\Http\Controllers\CategoriaServicioController::class, 'index'])->name('admin.categorias.index');
    Route::post('/admin/categorias', [App\Http\Controllers\CategoriaServicioController::class, 'store'])->name('admin.categorias.store');
    Route::put('/admin/categorias/{id}', [App\Http\Controllers\CategoriaServicioController::class, 'update'])->name('admin.categorias.update');
    Route::delete('/admin/categorias/{id}', [App\Http\Controllers\CategoriaServicioController::class, 'destroy'])->name('admin.categorias.destroy');
    // Route::get('/admin/secciones', [App\Http\Controllers\SeccionController::class, 'index'])->name('admin.secciones.index');
    // Route::post('/admin/secciones', [App\Http\Controllers\SeccionController::class, 'store'])->name('admin.secciones.store');
    // Route::put('/admin/secciones/{id}', [App\Http\Controllers\SeccionController::class, 'update'])->name('admin.secciones.update');
    // Route::delete('/admin/secciones/{id}', [App\Http\Controllers\SeccionController::class, 'destroy'])->name('admin.secciones.destroy');
    Route::get('admin/servicios/{id}/horarios', [App\Http\Controllers\ServicioHorarioController::class, 'edit'])->name('servicio.horarios.edit');
    Route::put('admin/servicios/{id}/horarios', [App\Http\Controllers\ServicioHorarioController::class, 'update'])->name('servicio.horarios.update');
   // Route::delete('admin/servicios/{id}/horarios/{idDia}/{horaInicio}', [App\Http\Controllers\ServicioHorarioController::class, 'destroy'])->name('servicio.horarios.destroy');
    Route::delete('/admin/servicios/{servicio}/horarios/{dia}/{hora}', [App\Http\Controllers\ServicioHorarioController::class, 'destroy'])->name('servicio.horario.destroy');



    Route::get('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscripciones.index');
    Route::put('/admin/inscripciones/{id}/cancelar', [App\Http\Controllers\InscripcionController::class, 'cancelar'])->name('admin.inscripciones.cancelar');
    Route::get('/admin/inscripciones/{id}/detalle', [App\Http\Controllers\InscripcionController::class, 'detalle'])->name('admin.inscripciones.detalle');
    Route::get('/admin/inscripciones/{id}/generar-credencial', [App\Http\Controllers\InscripcionController::class, 'generarCredencial'])->name('admin.inscripciones.generarCredencial');
    Route::get('/admin/inscripciones/{id}/enviar-whatsapp', [App\Http\Controllers\InscripcionController::class, 'enviarWhatsapp'])->name('admin.inscripciones.enviarWhatsapp');
    Route::get('/admin/inscripciones/{id}/generar-pase', [App\Http\Controllers\InscripcionController::class, 'generarPase'])->name('admin.inscripciones.generarPase');
    Route::get('/admin/inscripciones/crear', [App\Http\Controllers\InscripcionController::class, 'create'])->name('admin.inscripciones.create');
    Route::post('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store');

    Route::get('/admin/inscripciones/searchCliente', [App\Http\Controllers\InscripcionController::class, 'searchCliente'])->name('admin.inscripciones.searchCliente');
    Route::get('/admin/inscripciones/{id}', [App\Http\Controllers\InscripcionController::class, 'show'])->name('admin.inscripciones.show');

    Route::post('/admin/inscripciones/storeCliente', [App\Http\Controllers\InscripcionController::class, 'storeCliente'])->name('admin.inscripciones.storeCliente');

    Route::get('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index');
    Route::get('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create');
    Route::post('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store');
    Route::get('/admin/clientes/{id}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit');
    Route::put('/admin/clientes/{id}', [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update');
    Route::delete('/admin/clientes/{id}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy');
    //Route::get('/admin/clientes/profile', [App\Http\Controllers\ClienteController::class, 'profile'])->name('admin.clientes.profile');
    //Route::put('/admin/clientes/profile/update', [App\Http\Controllers\ClienteController::class, 'updateProfile'])->name('admin.clientes.updateProfile');
    //Route::delete('/admin/clientes/{id}/force', [App\Http\Controllers\ClienteController::class, 'forceDestroy'])->name('admin.clientes.forceDestroy');
    //Route::patch('/admin/clientes/{id}/restore', [App\Http\Controllers\ClienteController::class, 'restore'])->name('admin.clientes.restore');
    //Route::get('/admin/clientes/eliminados', [App\Http\Controllers\ClienteController::class, 'eliminados'])->name('admin.clientes.eliminados');
    //Route::get('admin/clientes/pdf', [App\Http\Controllers\ClienteController::class, 'exportPDF'])->name('admin.clientes.pdf');
    //Route::get('admin/clientes/export-excel', [App\Http\Controllers\ClienteController::class, 'exportExcel'])->name('admin.clientes.exportExcel');

    Route::get('/admin/asistencias', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('admin.asistencias.index');
    Route::post('/admin/asistencias/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrarAsistencia'])->name('admin.asistencias.registrar');
    Route::get('/admin/asistencias/buscar', [App\Http\Controllers\AsistenciaController::class, 'buscarCliente'])->name('admin.asistencias.buscar');
    Route::get('/admin/asistencias/lista', [App\Http\Controllers\AsistenciaController::class, 'verAsistencias'])->name('admin.asistencias.ver');
    //Route::put('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'update'])->name('admin.asistencias.update');
    Route::post('/admin/asistencias/anular/{idAsistencia}', [App\Http\Controllers\AsistenciaController::class, 'anularAsistencia'])->name('admin.asistencias.anular');
    Route::get('/admin/asistencias/{idAsistencia}/detalles', [App\Http\Controllers\AsistenciaController::class, 'anularAsistencia'])->name('admin.asistencias.detalles');



    Route::get('/admin/reportes/inscripciones', [App\Http\Controllers\ReportesController::class, 'inscripciones'])->name('admin.reportes.inscripciones');
    Route::get('/reportes/inscripciones/pdf', [App\Http\Controllers\ReportesController::class, 'generarPDFInscripciones'])->name('admin.reportes.inscripcionesPDF');
    Route::get('/reportes/inscripciones/excel', [App\Http\Controllers\ReportesController::class, 'generarExcelInscripciones'])->name('admin.reportes.inscripcionesExcel');
    Route::get('/admin/informes/inscripciones', [App\Http\Controllers\ReportesController::class, 'index'])->name('admin.reportes.informes');


    //reportes




    Route::get('/admin/reporte/clientes', [App\Http\Controllers\ReportesController::class, 'index'])->name('admin.reportes.cliente');
    Route::get('/admin/reporte/clientes/export/pdf', [App\Http\Controllers\ReportesController::class, 'exportarPDF'])->name('admin.reportes.clientes-pdf');
    Route::get('/admin/reporte/clientes/export/excel', [App\Http\Controllers\ReportesController::class, 'exportarExcel'])->name('admin.reporte.clientes-excel');

    Route::get('/admin/reportes/entrenadores', [App\Http\Controllers\ReportesEntrenadoresController::class, 'index'])->name('admin.reportes.entrenadores');
    Route::get('/admin/reportes/entrenadores-pdf', [App\Http\Controllers\ReportesEntrenadoresController::class, 'exportarPDF'])->name('admin.reportes.entrenadores-pdf');
    Route::get('/admin/reportes/entrenadores-excel', [App\Http\Controllers\ReportesEntrenadoresController::class, 'exportarExcel'])->name('admin.reporte.entrenadores-excel');

    Route::get('/admin/reportes/ingresos', [App\Http\Controllers\ReportesIngresosController::class, 'index'])->name('admin.reportes.ingresos');

    // Ruta para exportar el reporte de ingresos a PDF
    Route::get('/admin/reportes/ingresos-pdf', [App\Http\Controllers\ReportesIngresosController::class, 'exportarPDF'])->name('admin.reportes.ingresos-pdf');

    // Ruta para exportar el reporte de ingresos a Excel
    Route::get('/admin/reportes/ingresos-excel', [App\Http\Controllers\ReportesIngresosController::class, 'exportarExcel'])->name('admin.reportes.ingresos-excel');


    Route::get('/admin/reportes/asistencias', [App\Http\Controllers\ReportesAsistenciasController::class, 'index'])->name('admin.reportes.asistencias');
    Route::get('/admin/reportes/asistencias-pdf', [App\Http\Controllers\ReportesAsistenciasController::class, 'exportarPDF'])->name('admin.reportes.asistencias-pdf');
    Route::get('/admin/reportes/asistencias-excel', [App\Http\Controllers\ReportesAsistenciasController::class, 'exportarExcel'])->name('admin.reportes.asistencias-excel');



    Route::get('/admin/reportes/inscripciones-mes-anio', [App\Http\Controllers\ReportesInscripcionesController::class, 'index'])
        ->name('admin.reportes.inscripciones-mes-anio');

    // Ruta para exportar el reporte a PDF
    Route::get('/admin/reportes/inscripciones-mes-anio/pdf', [App\Http\Controllers\ReportesInscripcionesController::class, 'exportarPDF'])
        ->name('admin.reportes.inscripciones-pdf');

    // Ruta para exportar el reporte a Excel
    Route::get('/admin/reportes/inscripciones-excel', [App\Http\Controllers\ReportesInscripcionesController::class, 'exportarExcel'])->name('admin.reporte.inscripciones-excel');


    Route::get('/admin/ingresos-vendedor', [App\Http\Controllers\ReportesIngresosVendedorController::class, 'index'])->name('admin.reportes.ingresos-vendedor');
    Route::get('/admin/ingresos-vendedor-pdf', [App\Http\Controllers\ReportesIngresosVendedorController::class, 'exportarPDF'])->name('admin.reportes.ingresos-vendedor-pdf');


    Route::get('/admin/reportes/ingresos-membresias', [App\Http\Controllers\ReportesIngresosMembresiasController::class, 'index'])->name('admin.reportes.ingresos-membresias');
    Route::get('/admin/reportes/ingresos-membresias/pdf', [App\Http\Controllers\ReportesIngresosMembresiasController::class, 'exportarPDF'])->name('admin.reportes.ingresos-membresias-pdf');
    Route::get('/admin/reportes/ingresos-membresias/excel', [App\Http\Controllers\ReportesIngresosMembresiasController::class, 'exportarExcel'])->name('admin.reporte.ingresos-membresias-excel');


    Route::get('/admin/reportes/ingresos-servicios', [App\Http\Controllers\ReportesIngresosServiciosController::class, 'index'])->name('admin.reportes.ingresos-servicios');
    Route::get('/admin/reportes/ingresos-servicios/pdf', [App\Http\Controllers\ReportesIngresosServiciosController::class, 'exportarPDF'])->name('admin.reportes.ingresos-servicios-pdf');
    Route::get('/admin/reportes/ingresos-servicios/excel', [App\Http\Controllers\ReportesIngresosServiciosController::class, 'exportarExcel'])->name('admin.reportes.ingresos-servicios-excel');



    // qr asistencia

    Route::get('/admin/inscripciones/{id}/generar-qr', [App\Http\Controllers\InscripcionController::class, 'generarQr'])->name('admin.inscripciones.generarQr');

    Route::get('/admin/inscripciones/{id}/generar-qr2', [App\Http\Controllers\InscripcionController::class, 'generarQr2'])->name('admin.inscripciones.generarQr2');

    Route::get('/admin/inscripciones/{id}/comprobante', [App\Http\Controllers\InscripcionController::class, 'generarComprobante'])->name('admin.inscripciones.comprobante');

    //Route::get('/admin/inscripciones/{id}/comprobante', [App\Http\Controllers\InscripcionController::class, 'generarComprobanteTCPDF'])->name('admin.inscripciones.comprobante');


    Route::get('/admin/asistencias/qr-register', [App\Http\Controllers\AsistenciaController::class, 'qrRegister'])->name('admin.asistencias.qrRegister');
    Route::post('/admin/asistencias/registrarQR', [App\Http\Controllers\AsistenciaController::class, 'registrarQR'])->name('admin.asistencias.registrarQR');

});

Route::middleware(['auth'])->group(function () {







    //ruata ala que solo solo el cliente podra accedes
    Route::get('/cliente/dashboard', [App\Http\Controllers\ClienteController::class, 'dashboard'])->name('cliente.dashboard');
    Route::get('/cliente/asistencias', [App\Http\Controllers\ClienteController::class, 'asistencias'])->name('cliente.asistencias');
    ;
    Route::get('/clientes/entrenadores', [App\Http\Controllers\EntrenadorController::class, 'indexCliente'])->name('cliente.entrenadores.index');
    Route::get('/clientes/membresias', [App\Http\Controllers\MembresiaController::class, 'indexClinteM'])->name('cliente.membresias.index');
    Route::post('/clientes/membresias/solicitar', [App\Http\Controllers\MembresiaController::class, 'solicitar'])->name('cliente.membresias.solicitar');

    Route::get('/cliente/membresias/info', [App\Http\Controllers\MembresiaController::class, 'indexCredencial'])->name('cliente.membresias.info');
    Route::get('/cliente/membresias/imprimir', [App\Http\Controllers\MembresiaController::class, 'imprimirCredencial'])->name('cliente.membresias.credencial');
    Route::get('/clientes/asistencias', [App\Http\Controllers\AsistenciaController::class, 'mostrarAsistencias'])->name('cliente.asistencias.view');

});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/planes', [App\Http\Controllers\MembresiaController::class, 'index'])->name('admin.planes.index');
    Route::post('/admin/planes', [App\Http\Controllers\MembresiaController::class, 'store'])->name('admin.planes.store');
    Route::get('/admin/planes/{id}', [App\Http\Controllers\MembresiaController::class, 'show'])->name('admin.planes.show');
    Route::put('/admin/planes/{id}', [App\Http\Controllers\MembresiaController::class, 'update'])->name('admin.planes.update');
    Route::delete('/admin/planes/{id}', [App\Http\Controllers\MembresiaController::class, 'destroy'])->name('admin.planes.destroy');
    //modulo de inscripciones 


    //Route::get('/admin/inscripciones/crear', [App\Http\Controllers\InscripcionController::class, 'create'])->name('admin.inscripciones.create');
    //Route::post('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store');
    //Route::get('/admin/inscripciones/resgistro', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscripciones.index');
    //Route::get('/membresias/historial', [App\Http\Controllers\MembresiaController::class, 'historial'])->name('admin.membresias.historial');
    //Route::get('/admin/membresias/reporte', [App\Http\Controllers\MembresiaController::class, 'generarPDF'])->name('admin.membresias.generarPDF');

    //Route::get('/admin/membresias/credencial/{id}', [App\Http\Controllers\MembresiaController::class, 'generarCredencial'])->name('admin.membresias.generarCredencial');

    //route::get('/admin/inscripciones/cliente/{id}', [App\Http\Controllers\InscripcionController::class, 'obtenerCliente']);
    //Route::resource('inscripciones', App\Http\Controllers\InscripcionController::class)->except(['show']);

    // Rutas para actualizar el estado y el estado de pago
    //Route::post('inscripciones/{id}/estado', [App\Http\Controllers\InscripcionController::class, 'updateEstado'])->name('admin.inscripciones.updateEstado');
    //Route::post('inscripciones/{id}/estadoPago', [App\Http\Controllers\InscripcionController::class, 'updateEstadoPago'])->name('admin.inscripciones.updateEstadoPago');
    //Route::get('/admin/pagos', [App\Http\Controllers\PagoController::class, 'index'])->name('admin.pagos.index');
    // Route::get('/admin/pagos/reporte', [App\Http\Controllers\PagoController::class, 'generarReporte'])->name('admin.pagos.reporte');

});


Route::middleware(['auth'])->group(function () {

    Route::get('/admin/horarios', [App\Http\Controllers\HorarioController::class, 'index'])->name('admin.horarios.index');
    Route::get('/admin/horarios/create', [App\Http\Controllers\HorarioController::class, 'create'])->name('admin.horarios.create');
    Route::post('/admin/horarios', [App\Http\Controllers\HorarioController::class, 'store'])->name('admin.horarios.store');
    Route::get('/admin/horarios/{id}/edit', [App\Http\Controllers\HorarioController::class, 'edit'])->name('admin.horarios.edit');
    Route::put('/admin/horarios/{id}', [App\Http\Controllers\HorarioController::class, 'update'])->name('admin.horarios.update');
    Route::delete('/admin/horarios/{id}', [App\Http\Controllers\HorarioController::class, 'destroy'])->name('admin.horarios.destroy');





    //Route::get('/admin/asistencias', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('admin.asistencias.index');
    //Route::get('/admin/asistencias/exportar-pdf', [App\Http\Controllers\AsistenciaController::class, 'exportarPDF'])->name('admin.asistencias.asistencias-pdf');

    //Route::get('/admin/asistencias/registrar-cliente', [App\Http\Controllers\AsistenciaController::class, 'registro'])->name('admin.asistencias.cliente');
    //Route::post('/admin/asistencias/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrar'])->name('admin.asistencias.registrar');
    //Route::post('/admin/asistencias/registrar-salida', [App\Http\Controllers\AsistenciaController::class, 'registrarSalida'])->name('admin.asistencias.registrar-salida');
    //Route::get('/admin/asistencias/estadisticas', [App\Http\Controllers\AsistenciaController::class, 'estadisticas'])->name('admin.asistencias.estadisticas');

    //route::get('/admin/asistencias/{id}/edit', [App\Http\Controllers\AsistenciaController::class, 'edit'])->name('admin.asistencias.edit');



    //Route::put('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'update'])->name('admin.asistencias.update');




    //Route::delete('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'destroy'])->name('admin.asistencias.destroy');
    //Route::get('/admin/autocomplete-clientes', [App\Http\Controllers\AsistenciaController::class, 'autocompleteClientes'])->name('admin.autocomplete.clientes');
});
Route::middleware(['auth'])->group(function () {

    Route::get('/cliente/asistencia', [App\Http\Controllers\AsistenciaController::class, 'asistencias'])->name('cliente.asistencias.asistencia');
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

    Route::put('admin/pagos/{idPago}/updateEstado', [App\Http\Controllers\PagoController::class, 'updateEstado'])->name('admin.pagos.updateEstado');
    Route::put('admin/pagos/{idPago}/cancelar', [App\Http\Controllers\PagoController::class, 'cancelar'])->name('admin.pagos.cancelar');


    // Route::get('/admin/reportes/inscripciones', [App\Http\Controllers\ReporteInscripcionController::class, 'index'])->name('admin.reportes.inscripciones');
    // Route::get('/admin/reportes/exportPDF', [App\Http\Controllers\ReporteInscripcionController::class, 'exportPDF'])->name('admin.reportes.exportPDF');

    // Route::get('/admin/reportes/inscripciones', [App\Http\Controllers\ReporteInscripcionController::class, 'index'])->name('admin.reportes.inscripciones');

    // Ruta para Exportar en PDF
    //  Route::get('/admin/reportes/inscripciones/exportar/pdf', [App\Http\Controllers\ReporteInscripcionController::class, 'exportPDF'])->name('admin.reportes.inscripciones.exportar.pdf');

    // Ruta para Exportar en Excel
    // Route::get('/admin/reportes/inscripciones/exportar/excel', [App\Http\Controllers\ReporteInscripcionController::class, 'exportExcel'])->name('admin.reportes.inscripciones.exportar.excel');



    // Route::get('/admin/reportes/reservas', [App\Http\Controllers\ReporteReservasController::class, 'index'])->name('admin.reportes.reservas');

    //Route::get('admin/reportes/reservas/exportar-pdf', [App\Http\Controllers\ReporteReservasController::class, 'exportarPDF'])->name('admin.reportes.reservas.exportarPDF');
    //Route::get('admin/reportes/reservas/exportar-excel', [App\Http\Controllers\ReporteReservasController::class, 'exportarExcel'])->name('admin.reportes.reservas.exportarExcel');


    //reportes de pagos

    ///Route::get('/reporte-pagos', [App\Http\Controllers\ReportePagosController::class, 'index'])->name('admin.reportes.pagos');
    //Route::get('/reporte-pagos/data', [App\Http\Controllers\ReportePagosController::class, 'getPagos'])->name('pagos.get');
    //Route::get('/reporte-pagos/export-pdf', [App\Http\Controllers\ReportePagosController::class, 'exportPDF'])->name('pagos.exportPDF');
    //Route::get('/reporte-pagos/export-excel', [App\Http\Controllers\ReportePagosController::class, 'exportExcel'])->name('pagos.exportExcel');



    // Route::get('admin/reportes/asistencias', [App\Http\Controllers\ReporteAsistenciasController::class, 'index'])->name('admin.reportes.asistencias');
    //Route::get('admin/reportes/asistencias/export/excel', [App\Http\Controllers\ReporteAsistenciasController::class, 'exportExcel'])->name('admin.asistencias.reporte_excel');
    //Route::get('admin/reportes/asistencias/export/pdf', [App\Http\Controllers\ReporteAsistenciasController::class, 'exportPDF'])->name('admin.asistencias.reporte_pdf');
    // Route::get('clientes/search', [App\Http\Controllers\ReporteAsistenciasController::class, 'searchClientes'])->name('clientes.search');


    // Route::get('reservas/{id}/comprobante', [App\Http\Controllers\ReservaController::class, 'generarComprobante'])->name('admin.reservas.comprobante');


});



//configuracion del envio de email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('password.change');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'showChangeForm'])->name('password.change');
Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'changePassword'])->name('password.change.submit');


use App\Http\Controllers\QRCheckController;

Route::get('/qr/all', [QRCheckController::class, 'generateAllQR'])->name('qr.generateAll');

Route::get('/qr', [QRCheckController::class, 'index'])->name('qr.index');

Route::get('/qr/{cliente}', [QRCheckController::class, 'generateQR'])->name('qr.generate');
Route::post('/check', [QRCheckController::class, 'processCheck'])->name('qr.check');
Route::get('/scanner', [QRCheckController::class, 'show'])->name('qr.scanner');
Route::post('/scanner/process', [QRCheckController::class, 'process'])->name('qr.process');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
