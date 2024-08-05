@extends('layouts.admin')
@section('content')
    <!--Panel de contenido-->
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">Form Stuff</a></li>
        <li class="breadcrumb-item active">Formulario</li>
    </ol>

    <h1 class="page-header">Añadir usuario</h1>

    <div class="row">
        <div class="col-xl-8">
            <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                <div class="panel-heading">
                    <h4 class="panel-title">Ingrese sus datos</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" data-parsley-validate="true" name="demo-form" action="{{url('/admin/usuarios/create')}}" method="POST">
                        @csrf
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="fullname">Nombre de Usuario * :</label>
                            <div class="col-lg-8">
                                <input class="form-control" value="{{old('name')}}" type="text" id="name" name="name"
                                    placeholder="nombre de usuario" data-parsley-required="true" required/>
                                @error('name')
                                    <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="email">Email * :</label>
                            <div class="col-lg-8">
                                <input class="form-control" value="{{old('email')}}" type="email" id="email" name="email"
                                    placeholder="Email" data-parsley-required="true" required/>
                                @error('email')
                                    <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label">Password *:</label>
                            <div class="col-lg-8">
                                <input type="password" value="{{old('password')}}" name="password" class="form-control" placeholder="Password" required/>
                                @error('password')
                                    <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label">Password Verificacion *:</label>
                            <div class="col-lg-8">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Password confirmation" required/>
                                @error('password_confirmation')
                                    <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="nombre">Nombre * :</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="primerApellido">Primer Apellido * :</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control @error('primerApellido') is-invalid @enderror" id="primerApellido" name="primerApellido" value="{{ old('primerApellido') }}" required>
                                @error('primerApellido')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="segundoApellido">Segundo Apellido:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control @error('segundoApellido') is-invalid @enderror" id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido') }}">
                                @error('segundoApellido')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="fechaNacimiento">Fecha de Nacimiento * :</label>
                            <div class="col-lg-8">
                                <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" required>
                                @error('fechaNacimiento')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="direccion">Dirección:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">
                                @error('direccion')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="genero">Género * :</label>
                            <div class="col-lg-8">
                                <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero" required>
                                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('genero')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="telefono">Teléfono:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                                @error('telefono')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="telefonoEmergencia">Teléfono de Emergencia:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control @error('telefonoEmergencia') is-invalid @enderror" id="telefonoEmergencia" name="telefonoEmergencia" value="{{ old('telefonoEmergencia') }}">
                                @error('telefonoEmergencia')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-lg-4 col-form-label form-label" for="rol">Rol *:</label>
                            <div class="col-lg-8">
                                <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                                    <option value="Empleado" {{ old('rol') == 'Empleado' ? 'selected' : '' }}>Empleado</option>
                                    <option value="Cliente" {{ old('rol') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                                
                                </select>
                                @error('rol')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3" id="camposEmpleado" style="display: none;">
                            <div class="col-lg-6">
                                <label class="col-form-label form-label" for="fechaContratacion">Fecha de Contratación * :</label>
                                <input type="date" class="form-control @error('fechaContratacion') is-invalid @enderror" id="fechaContratacion" name="fechaContratacion" value="{{ old('fechaContratacion') }}">
                                @error('fechaContratacion')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label form-label" for="puesto">Puesto * :</label>
                                <input type="text" class="form-control @error('puesto') is-invalid @enderror" id="puesto" name="puesto" value="{{ old('puesto') }}">
                                @error('puesto')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label form-label">&nbsp;</label>
                            <div class="col-lg-8">
                                <a href="{{url('admin/usuarios')}}" class="btn btn-danger">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Registrar usuario</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end panel de contenido-->
@endsection
