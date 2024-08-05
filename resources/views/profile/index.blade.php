@extends('layouts.admin')
@section('content')
 <!-- Page Header -->
 <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Editar Perfil</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Pigina</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Perfil</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-info btn-icon me-2 btn-b"><i class="mdi mdi-filter-variant"></i></button>
        </div>
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-danger btn-icon me-2"><i class="mdi mdi-star"></i></button>
        </div>
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-warning  btn-icon me-2"><i class="mdi mdi-refresh"></i></button>
        </div>
        <div class="mb-xl-0">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
                    14 Aug 2019
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate">
                    <li><a class="dropdown-item" href="javascript:void(0);">2015</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2016</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2017</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2018</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page Header Close -->

<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4">
            <div class="card-body">
                <div class="ps-0">
                    <div class="main-profile-overview">
                        <div class="main-img-user profile-user">
                            <img alt="Foto de perfil" src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-profile.png') }}">
                            <a class="fe fe-camera profile-edit text-primary" href="JavaScript:void(0);"></a>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <h5 class="main-profile-name">{{ $user->nombreUsuario }}</h5>
                            </div>
                        </div>
                        <h6 class="fs-14">Biografia</h6>
                        <div class="main-profile-bio">
                            
                        </div>
                        <hr class="border-1">
                        <h6 class="fs-14">Nombre completo</h6>
                        <div>                      
                            <p class="main-profile-name-text">
                            
                            </p>
                        </div>
                        <hr class="border-0">
                        <h6 class="fs-14">Edad</h6>
                        <div>                      
                            <p class="main-profile-name-text">
                            
                            </p>
                        </div>
                        <hr class="border-0">
                        <h6 class="fs-14">Fecha de Contratación</h6>
                        <div>                      
                            <p class="main-profile-name-text">
                                
                            </p>
                        </div>
                        <!--skill bar-->
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="main-content-label tx-13 mg-b-25">
                    contacto
                </div>
                <div class="main-profile-contact-list">
                    <div class="media">
                        <div class="media-icon bg-primary-transparent text-primary">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="media-body">
                            <span>telefono</span>
                            <div>
                                {{ $user->telefono }}
                            </div>
                        </div>
                    </div>
                    <div class="media">
                        <div class="media-icon bg-success-transparent text-success">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="media-body">
                            <span>Correo Electronico</span>
                            <div>
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-body">
                <div class="mb-4 main-content-label">Información Personal</div>
                <form class="row g-3 needs-validation" novalidate action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="updateProfileForm">
                    @csrf
                    @method('PUT')

                    <!-- Mensajes de error -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4 main-content-label">Datos </div>
                    
                    
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Nombre de Usuario <span style="color:red">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nombreUsuario" value="{{ old('nombreUsuario', $user->nombreUsuario) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 main-content-label">Informacion de contacto y seguridad</div>
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Correo Electronico<span style="color:red">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Telefono</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="telefono" value="{{ $user->telefono }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Contraseña (dejar en blanco si no se quiere cambiar)</label>
                            </div>
                            <div class="col-md-9">
                                <input class="form-control" type="password" placeholder="8 - 15 Characteres" id="password" name="password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Confirmacion de Contraseña</label>
                            </div>
                            <div class="col-md-9">
                                <input class="form-control" type="password" placeholder="8 - 15 Characteres" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End::row-1 -->

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Éxito',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('updateProfileForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, actualizar perfil!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection


