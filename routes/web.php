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
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/planes', [App\Http\Controllers\PlanMembresiaController::class, 'index'])->name('admin.planes.index');
    Route::get('/admin/planes/create', [App\Http\Controllers\PlanMembresiaController::class, 'create'])->name('admin.planes.create');
    Route::post('/admin/planes', [App\Http\Controllers\PlanMembresiaController::class, 'store'])->name('admin.planes.store');
    Route::get('/admin/planes/{id}/edit', [App\Http\Controllers\PlanMembresiaController::class, 'edit'])->name('admin.planes.edit');
    Route::put('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'update'])->name('admin.planes.update');
    Route::delete('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'destroy'])->name('admin.planes.destroy');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/horarios', [App\Http\Controllers\HorarioController::class, 'index'])->name('admin.horarios.index');
        //Route::get('/admin/planes/create', [App\Http\Controllers\PlanMembresiaController::class, 'create'])->name('admin.planes.create');
        //Route::post('/admin/planes', [App\Http\Controllers\PlanMembresiaController::class, 'store'])->name('admin.planes.store');
        //Route::get('/admin/planes/{id}/edit', [App\Http\Controllers\PlanMembresiaController::class, 'edit'])->name('admin.planes.edit');
        //Route::put('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'update'])->name('admin.planes.update');
        //Route::delete('/admin/planes/{id}', [App\Http\Controllers\PlanMembresiaController::class, 'destroy'])->name('admin.planes.destroy');
        });


    //configuracion del envio de email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
    
        return redirect()->route('password.change');
    })->middleware(['auth', 'signed'])->name('verification.verify');


    Route::get('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'showChangeForm'])->name('password.change');
Route::post('/password/change', [App\Http\Controllers\PasswordChangeController::class, 'changePassword'])->name('password.change.submit');
