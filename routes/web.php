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
//ruta para los usuarios
//Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.indexUser')->middleware('auth');

                //ruta para creacion de usuarios
//Route::get('/admin/usuarios/crea', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');

//Route::post('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');

//Route::get('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');                
//ruta para update
//Route::get('/admin/usuarios/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');  
//Route::put('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth'); 
//Route::delete('/admin/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');



// Rutas para el perfil del usuario autenticado
Route::get('/profile/show', [App\Http\Controllers\UsuarioController::class, 'profile'])->name('profile.index')->middleware('auth');
Route::put('/profile/update', [App\Http\Controllers\UsuarioController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/empleados', [App\Http\Controllers\EmpleadoController::class, 'index'])->name('admin.empleados.index');
    Route::get('/admin/empleados/create', [App\Http\Controllers\EmpleadoController::class, 'create'])->name('admin.empleados.create');
    Route::post('/admin/empleados', [App\Http\Controllers\EmpleadoController::class, 'store'])->name('admin.empleados.store');
    Route::get('/admin/empleados/{id}/edit', [App\Http\Controllers\EmpleadoController::class, 'edit'])->name('admin.empleados.edit');
    Route::put('/admin/empleados/{id}', [App\Http\Controllers\EmpleadoController::class, 'update'])->name('admin.empleados.update');
    Route::delete('/admin/empleados/{id}', [App\Http\Controllers\EmpleadoController::class, 'destroy'])->name('admin.empleados.destroy');
    Route::get('/admin/empleados/profile', [App\Http\Controllers\EmpleadoController::class, 'profile'])->name('admin.empleados.profile');
    Route::put('/admin/empleados/profile/update', [App\Http\Controllers\EmpleadoController::class, 'updateProfile'])->name('admin.empleados.updateProfile');
    
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
