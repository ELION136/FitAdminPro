@extends('layouts.admin')
@section('content')
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Modificar Usuario</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">Usuarios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Modificar Usuario</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Modificar datos</div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ url('/admin/usuarios', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Datos del usuario -->
                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userName">Nombre de Usuario * :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userName" name="name"
                                value="{{ old('name', $usuario->name) }}" placeholder="Nombre de usuario" required/>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userEmail">Email * :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="email" id="userEmail" name="email"
                                value="{{ old('email', $usuario->email) }}" placeholder="Email" required/>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userPassword">Contraseña :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="password" id="userPassword" name="password"
                                placeholder="Contraseña (si desea cambiarla)"/>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userPasswordConfirmation">Confirmar Contraseña :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="password" id="userPasswordConfirmation" name="password_confirmation"
                                placeholder="Confirmar Contraseña"/>
                        </div>
                    </div>

                    <!-- Datos de la persona -->
                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userNombre">Nombre * :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userNombre" name="nombre"
                                value="{{ old('nombre', $usuario->persona->nombre) }}" placeholder="Nombre" required/>
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userPrimerApellido">Primer Apellido * :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userPrimerApellido" name="primerApellido"
                                value="{{ old('primerApellido', $usuario->persona->primerApellido) }}" placeholder="Primer Apellido" required/>
                            @error('primerApellido')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userSegundoApellido">Segundo Apellido :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userSegundoApellido" name="segundoApellido"
                                value="{{ old('segundoApellido', $usuario->persona->segundoApellido) }}" placeholder="Segundo Apellido"/>
                            @error('segundoApellido')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userFechaNacimiento">Fecha de Nacimiento * :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="date" id="userFechaNacimiento" name="fechaNacimiento"
                                value="{{ old('fechaNacimiento', $usuario->persona->fechaNacimiento) }}" required/>
                            @error('fechaNacimiento')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userDireccion">Dirección :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userDireccion" name="direccion"
                                value="{{ old('direccion', $usuario->persona->direccion) }}" placeholder="Dirección"/>
                            @error('direccion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userGenero">Género * :</label>
                        <div class="col-lg-8">
                            <select class="form-control" id="userGenero" name="genero" required>
                                <option value="Masculino" {{ old('genero', $usuario->persona->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $usuario->persona->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $usuario->persona->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userTelefono">Teléfono :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userTelefono" name="telefono"
                                value="{{ old('telefono', $usuario->persona->telefono) }}" placeholder="Teléfono"/>
                            @error('telefono')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-lg-4 col-form-label form-label" for="userTelefonoEmergencia">Teléfono de Emergencia :</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="text" id="userTelefonoEmergencia" name="telefonoEmergencia"
                                value="{{ old('telefonoEmergencia', $usuario->persona->telefonoEmergencia) }}" placeholder="Teléfono de Emergencia"/>
                            @error('telefonoEmergencia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-lg-8 offset-lg-4">
                            <a href="{{ route('admin.usuarios.indexUser') }}" class="btn btn-danger">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
