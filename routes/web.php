<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\QRCheckController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Authentication Routes
Auth::routes(['verify' => true]);
Auth::routes(); // Additional auth routes if needed

// Public Routes
Route::get('/', function () {
    return view('index');
});

// Password Reset Custom Routes
Route::get('/custom-reset-password', function () {
    return view('custom_reset_password');
})->name('password.custom_reset_form');

Route::post('/custom-password-reset', [App\Http\Controllers\CustomPasswordResetController::class, 'sendResetLink'])
    ->name('password.custom_reset');

Route::post('/custom-reset-password', [App\Http\Controllers\CustomPasswordResetController::class, 'resetPassword'])
    ->name('password.custom_update');

// Email Verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('password.change');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Password Change Routes
Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'showChangeForm'])
    ->name('password.change');

Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'changePassword'])
    ->name('password.change.submit');

// QR Code Routes
Route::get('/qr/all', [QRCheckController::class, 'generateAllQR'])->name('qr.generateAll');
Route::get('/qr', [QRCheckController::class, 'index'])->name('qr.index');
Route::get('/qr/{cliente}', [QRCheckController::class, 'generateQR'])->name('qr.generate');
Route::post('/check', [QRCheckController::class, 'processCheck'])->name('qr.check');
Route::get('/scanner', [QRCheckController::class, 'show'])->name('qr.scanner');
Route::post('/scanner/process', [QRCheckController::class, 'process'])->name('qr.process');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Home Dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/show', [App\Http\Controllers\UsuarioController::class, 'profile'])->name('profile.index');
        Route::put('/update', [App\Http\Controllers\UsuarioController::class, 'updateProfile'])->name('profile.update');
        Route::post('/validate', [App\Http\Controllers\UsuarioController::class, 'validateProfile'])->name('profile.validate');
    });

    // Admin Routes Group
    Route::prefix('admin')->group(function () {
        // Admin Dashboard
        Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/home', [App\Http\Controllers\AdminController::class, 'home'])->name('admin.home');

        // User Management
        Route::prefix('usuarios')->group(function () {
            Route::get('/', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index');
            Route::post('/store', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store');
            Route::put('/update/{idUsuario}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update');
            Route::delete('/destroy/{idUsuario}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
            Route::put('/toggleStatus/{idUsuario}', [App\Http\Controllers\UsuarioController::class, 'toggleStatus'])->name('admin.usuarios.toggleStatus');
            Route::post('/validate', [App\Http\Controllers\UsuarioController::class, 'validateField'])->name('admin.usuarios.validate');
        });

        // Trainer Management
        Route::prefix('entrenadores')->group(function () {
            Route::get('/', [App\Http\Controllers\EntrenadorController::class, 'index'])->name('admin.entrenadores.index');
            Route::get('/create', [App\Http\Controllers\EntrenadorController::class, 'create'])->name('admin.entrenadores.create');
            Route::post('/', [App\Http\Controllers\EntrenadorController::class, 'store'])->name('admin.entrenadores.store');
            Route::get('/{id}/edit', [App\Http\Controllers\EntrenadorController::class, 'edit'])->name('admin.entrenadores.edit');
            Route::put('/{id}', [App\Http\Controllers\EntrenadorController::class, 'update'])->name('admin.entrenadores.update');
            Route::delete('/{id}', [App\Http\Controllers\EntrenadorController::class, 'destroy'])->name('admin.entrenadores.destroy');
            Route::post('/validateField', [App\Http\Controllers\EntrenadorController::class, 'validateField'])
                ->name('admin.entrenadores.validateField');
        });

        // Membership Management
        Route::prefix('membresias')->group(function () {
            Route::get('/', [App\Http\Controllers\MembresiaController::class, 'index'])->name('admin.membresias.index');
            Route::post('/', [App\Http\Controllers\MembresiaController::class, 'store'])->name('admin.membresias.store');
            Route::put('/{id}', [App\Http\Controllers\MembresiaController::class, 'update'])->name('admin.membresias.update');
            Route::delete('/{id}', [App\Http\Controllers\MembresiaController::class, 'destroy'])->name('admin.membresias.destroy');
        });

        // Service Management
        Route::prefix('servicios')->group(function () {
            Route::get('/', [App\Http\Controllers\ServiciosController::class, 'index'])->name('admin.servicios.index');
            Route::post('/', [App\Http\Controllers\ServiciosController::class, 'store'])->name('admin.servicios.store');
            Route::put('/{id}', [App\Http\Controllers\ServiciosController::class, 'update'])->name('admin.servicios.update');
            Route::delete('/{id}', [App\Http\Controllers\ServiciosController::class, 'destroy'])->name('admin.servicios.destroy');
            Route::get('/{servicio}/edit', [App\Http\Controllers\ServiciosController::class, 'edit'])->name('admin.servicios.edit');
            
            // Service Schedules
            Route::get('/{id}/horarios', [App\Http\Controllers\ServicioHorarioController::class, 'edit'])->name('servicio.horarios.edit');
            Route::put('/{id}/horarios', [App\Http\Controllers\ServicioHorarioController::class, 'update'])->name('servicio.horarios.update');
            Route::delete('/{servicio}/horarios/{dia}/{hora}', [App\Http\Controllers\ServicioHorarioController::class, 'destroy'])
                ->name('servicio.horario.destroy');
            Route::get('/calendario', [App\Http\Controllers\ServiciosController::class, 'calendarioGeneral'])
                ->name('admin.servicios.calendarioGeneral');
        });

        // Category Management
        Route::prefix('categorias')->group(function () {
            Route::get('/', [App\Http\Controllers\CategoriaServicioController::class, 'index'])->name('admin.categorias.index');
            Route::post('/', [App\Http\Controllers\CategoriaServicioController::class, 'store'])->name('admin.categorias.store');
            Route::put('/{id}', [App\Http\Controllers\CategoriaServicioController::class, 'update'])->name('admin.categorias.update');
            Route::delete('/{id}', [App\Http\Controllers\CategoriaServicioController::class, 'destroy'])->name('admin.categorias.destroy');
        });

        // Enrollment Management
        Route::prefix('inscripciones')->group(function () {
            Route::get('/', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscripciones.index');
            Route::get('/crear', [App\Http\Controllers\InscripcionController::class, 'create'])->name('admin.inscripciones.create');
            Route::post('/', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store');
            Route::put('/{id}/cancelar', [App\Http\Controllers\InscripcionController::class, 'cancelar'])->name('admin.inscripciones.cancelar');
            Route::get('/{id}/detalle', [App\Http\Controllers\InscripcionController::class, 'detalle'])->name('admin.inscripciones.detalle');
            Route::get('/{id}/generar-credencial', [App\Http\Controllers\InscripcionController::class, 'generarCredencial'])->name('admin.inscripciones.generarCredencial');
            Route::get('/{id}/enviar-whatsapp', [App\Http\Controllers\InscripcionController::class, 'enviarWhatsapp'])->name('admin.inscripciones.enviarWhatsapp');
            Route::get('/{id}/generar-pase', [App\Http\Controllers\InscripcionController::class, 'generarPase'])->name('admin.inscripciones.generarPase');
            Route::get('/searchCliente', [App\Http\Controllers\InscripcionController::class, 'searchCliente'])->name('admin.inscripciones.searchCliente');
            Route::get('/{id}', [App\Http\Controllers\InscripcionController::class, 'show'])->name('admin.inscripciones.show');
            Route::post('/storeCliente', [App\Http\Controllers\InscripcionController::class, 'storeCliente'])->name('admin.inscripciones.storeCliente');
            Route::get('/comprobante/{id}', [App\Http\Controllers\InscripcionController::class, 'generarPDF'])->name('admin.inscripciones.generarPDF');
            Route::get('/{id}/detalle-servicios', [App\Http\Controllers\InscripcionController::class, 'detalleServicios'])->name('admin.inscripciones.detalleServicios');
            Route::put('/{id}/marcar-vencida', [App\Http\Controllers\InscripcionController::class, 'marcarComoVencida'])->name('admin.inscripciones.marcarVencida');
            Route::get('/{id}/generar-qr', [App\Http\Controllers\InscripcionController::class, 'generarQr'])->name('admin.inscripciones.generarQr');
            Route::get('/{id}/generar-qr2', [App\Http\Controllers\InscripcionController::class, 'generarQr2'])->name('admin.inscripciones.generarQr2');
            Route::get('/comprobanteFinal/{id}', [App\Http\Controllers\InscripcionController::class, 'generarComprobante'])->name('admin.inscripciones.comprobante2');
            Route::get('/comprobante-servicio/{idDetalle}', [App\Http\Controllers\InscripcionController::class, 'generarComprobanteServicio'])->name('admin.inscripciones.comprobante_servicio');
        });

        // Client Management
        Route::prefix('clientes')->group(function () {
            Route::get('/', [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index');
            Route::get('/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create');
            Route::post('/', [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store');
            Route::get('/{id}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit');
            Route::put('/{id}', [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update');
            Route::delete('/{id}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy');
        });

        // Attendance Management
        Route::prefix('asistencias')->group(function () {
            Route::get('/', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('admin.asistencias.index');
            Route::post('/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrarAsistencia'])->name('admin.asistencias.registrar');
            Route::get('/buscar', [App\Http\Controllers\AsistenciaController::class, 'buscarCliente'])->name('admin.asistencias.buscar');
            Route::get('/lista', [App\Http\Controllers\AsistenciaController::class, 'verAsistencias'])->name('admin.asistencias.ver');
            Route::post('/anular/{idAsistencia}', [App\Http\Controllers\AsistenciaController::class, 'anularAsistencia'])->name('admin.asistencias.anular');
            Route::get('/{idAsistencia}/detalles', [App\Http\Controllers\AsistenciaController::class, 'anularAsistencia'])->name('admin.asistencias.detalles');
            Route::get('/qr-register', [App\Http\Controllers\AsistenciaController::class, 'qrRegister'])->name('admin.asistencias.qrRegister');
            Route::post('/registrarQR', [App\Http\Controllers\AsistenciaController::class, 'registrarQR'])->name('admin.asistencias.registrarQR');
        });

        // Plan Management
        Route::prefix('planes')->group(function () {
            Route::get('/', [App\Http\Controllers\MembresiaController::class, 'index'])->name('admin.planes.index');
            Route::post('/', [App\Http\Controllers\MembresiaController::class, 'store'])->name('admin.planes.store');
            Route::get('/{id}', [App\Http\Controllers\MembresiaController::class, 'show'])->name('admin.planes.show');
            Route::put('/{id}', [App\Http\Controllers\MembresiaController::class, 'update'])->name('admin.planes.update');
            Route::delete('/{id}', [App\Http\Controllers\MembresiaController::class, 'destroy'])->name('admin.planes.destroy');
        });

        // Schedule Management
        Route::prefix('horarios')->group(function () {
            Route::get('/', [App\Http\Controllers\HorarioController::class, 'index'])->name('admin.horarios.index');
            Route::get('/create', [App\Http\Controllers\HorarioController::class, 'create'])->name('admin.horarios.create');
            Route::post('/', [App\Http\Controllers\HorarioController::class, 'store'])->name('admin.horarios.store');
            Route::get('/{id}/edit', [App\Http\Controllers\HorarioController::class, 'edit'])->name('admin.horarios.edit');
            Route::put('/{id}', [App\Http\Controllers\HorarioController::class, 'update'])->name('admin.horarios.update');
            Route::delete('/{id}', [App\Http\Controllers\HorarioController::class, 'destroy'])->name('admin.horarios.destroy');
        });

        // Payment Management
        Route::prefix('pagos')->group(function () {
            Route::put('/{idPago}/updateEstado', [App\Http\Controllers\PagoController::class, 'updateEstado'])->name('admin.pagos.updateEstado');
            Route::put('/{idPago}/cancelar', [App\Http\Controllers\PagoController::class, 'cancelar'])->name('admin.pagos.cancelar');
        });

        // QR Generation for Memberships and Services
        Route::get('/generar-qr-membresia/{idCliente}', [App\Http\Controllers\InscripcionController::class, 'generarQrParaMembresia'])
            ->name('admin.generar.qr-membresia');
        Route::post('/generar-qr-servicio/{idDetalleInscripcion}', [App\Http\Controllers\InscripcionController::class, 'generarQrParaServicio'])
            ->name('admin.generar.qr-servicio');

        // Reporting Routes
        Route::prefix('reportes')->group(function () {
            // Enrollment Reports
            Route::get('/inscripciones', [App\Http\Controllers\ReportesController::class, 'inscripciones'])->name('admin.reportes.inscripciones');
            Route::get('/inscripciones/pdf', [App\Http\Controllers\ReportesController::class, 'generarPDFInscripciones'])->name('admin.reportes.inscripcionesPDF');
            Route::get('/inscripciones/excel', [App\Http\Controllers\ReportesController::class, 'generarExcelInscripciones'])->name('admin.reportes.inscripcionesExcel');
            Route::get('/informes/inscripciones', [App\Http\Controllers\ReportesController::class, 'index'])->name('admin.reportes.informes');
            
            // Client Reports
            Route::get('/reporte/clientes', [App\Http\Controllers\ReportesController::class, 'index'])->name('admin.reportes.cliente');
            Route::get('/reporte/clientes/export/pdf', [App\Http\Controllers\ReportesController::class, 'exportarPDF'])->name('admin.reportes.clientes-pdf');
            Route::get('/reporte/clientes/export/excel', [App\Http\Controllers\ReportesController::class, 'exportarExcel'])->name('admin.reporte.clientes-excel');
            
            // Trainer Reports
            Route::get('/entrenadores', [App\Http\Controllers\ReportesEntrenadoresController::class, 'index'])->name('admin.reportes.entrenadores');
            Route::get('/entrenadores-pdf', [App\Http\Controllers\ReportesEntrenadoresController::class, 'exportarPDF'])->name('admin.reportes.entrenadores-pdf');
            Route::get('/entrenadores-excel', [App\Http\Controllers\ReportesEntrenadoresController::class, 'exportarExcel'])->name('admin.reporte.entrenadores-excel');
            
            // Income Reports
            Route::get('/ingresos', [App\Http\Controllers\ReportesIngresosController::class, 'index'])->name('admin.reportes.ingresos');
            Route::get('/ingresos-pdf', [App\Http\Controllers\ReportesIngresosController::class, 'exportarPDF'])->name('admin.reportes.ingresos-pdf');
            Route::get('/ingresos-excel', [App\Http\Controllers\ReportesIngresosController::class, 'exportarExcel'])->name('admin.reportes.ingresos-excel');
            
            // Attendance Reports
            Route::get('/asistencias', [App\Http\Controllers\ReportesAsistenciasController::class, 'index'])->name('admin.reportes.asistencias');
            Route::get('/asistencias-pdf', [App\Http\Controllers\ReportesAsistenciasController::class, 'exportarPDF'])->name('admin.reportes.asistencias-pdf');
            Route::get('/asistencias-excel', [App\Http\Controllers\ReportesAsistenciasController::class, 'exportarExcel'])->name('admin.reportes.asistencias-excel');
            
            // Enrollment by Month/Year Reports
            Route::get('/inscripciones-mes-anio', [App\Http\Controllers\ReportesInscripcionesController::class, 'index'])
                ->name('admin.reportes.inscripciones-mes-anio');
            Route::get('/inscripciones-mes-anio/pdf', [App\Http\Controllers\ReportesInscripcionesController::class, 'exportarPDF'])
                ->name('admin.reportes.inscripciones-pdf');
            Route::get('/inscripciones-excel', [App\Http\Controllers\ReportesInscripcionesController::class, 'exportarExcel'])
                ->name('admin.reporte.inscripciones-excel');
            
            // Salesperson Income Reports
            Route::get('/ingresos-vendedor', [App\Http\Controllers\ReportesIngresosVendedorController::class, 'index'])
                ->name('admin.reportes.ingresos-vendedor');
            Route::get('/ingresos-vendedor-pdf', [App\Http\Controllers\ReportesIngresosVendedorController::class, 'exportarPDF'])
                ->name('admin.reportes.ingresos-vendedor-pdf');
            
            // Membership Income Reports
            Route::get('/ingresos-membresias', [App\Http\Controllers\ReportesIngresosMembresiasController::class, 'index'])
                ->name('admin.reportes.ingresos-membresias');
            Route::get('/ingresos-membresias/pdf', [App\Http\Controllers\ReportesIngresosMembresiasController::class, 'exportarPDF'])
                ->name('admin.reportes.ingresos-membresias-pdf');
            Route::get('/ingresos-membresias/excel', [App\Http\Controllers\ReportesIngresosMembresiasController::class, 'exportarExcel'])
                ->name('admin.reporte.ingresos-membresias-excel');
            
            // Service Income Reports
            Route::get('/ingresos-servicios', [App\Http\Controllers\ReportesIngresosServiciosController::class, 'index'])
                ->name('admin.reportes.ingresos-servicios');
            Route::get('/ingresos-servicios/pdf', [App\Http\Controllers\ReportesIngresosServiciosController::class, 'exportarPDF'])
                ->name('admin.reportes.ingresos-servicios-pdf');
            Route::get('/ingresos-servicios/excel', [App\Http\Controllers\ReportesIngresosServiciosController::class, 'exportarExcel'])
                ->name('admin.reportes.ingresos-servicios-excel');
        });
    });

    // Client Facing Routes
    Route::prefix('cliente')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\ClienteController::class, 'dashboard'])->name('cliente.dashboard');
        
        // Memberships
        Route::get('/membresias', [App\Http\Controllers\MembresiaController::class, 'indexClinteM'])->name('cliente.membresias.index');
        Route::post('/membresias/solicitar', [App\Http\Controllers\MembresiaController::class, 'solicitar'])->name('cliente.membresias.solicitar');
        Route::get('/membresias/info', [App\Http\Controllers\MembresiaController::class, 'indexCredencial'])->name('cliente.membresias.info');
        Route::get('/membresias/imprimir', [App\Http\Controllers\MembresiaController::class, 'imprimirCredencial'])->name('cliente.membresias.credencial');
        
        // Trainers
        Route::get('/entrenadores', [App\Http\Controllers\EntrenadorController::class, 'indexCliente'])->name('cliente.entrenadores.index');
        
        // Attendance
        Route::get('/asistencias', [App\Http\Controllers\AsistenciaController::class, 'asistencias'])->name('cliente.asistencias');
        Route::get('/asistencias/view', [App\Http\Controllers\AsistenciaController::class, 'mostrarAsistencias'])->name('cliente.asistencias.view');
        Route::get('/asistencia', [App\Http\Controllers\AsistenciaController::class, 'asistencias'])->name('cliente.asistencias.asistencia');
        Route::post('/asistencia/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrar'])->name('cliente.asistencias.registrar-asistencia');
        Route::get('/asistencia/reporte', [App\Http\Controllers\AsistenciaController::class, 'reporte'])->name('cliente.asistencias.reporte-asistencia');
        Route::put('/asistencia/{asistencia}', [App\Http\Controllers\AsistenciaController::class, 'corregirAsistencia'])->name('cliente.asistencias.corregir-asistencia');
        Route::delete('/asistencia/{asistencia}', [App\Http\Controllers\AsistenciaController::class, 'eliminarAsistencia'])->name('cliente.asistencias.eliminar-asistencia');
    });
});

// Additional Attendance QR Registration
Route::post('/asistencias/registrarQRindex', [App\Http\Controllers\AsistenciaController::class, 'registrarQR1'])->name('asistencias.registrarQRb');