@extends('layouts.app')
@section('content')

<div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
    <div class="row g-4">
        <div class="col-auto">
            <div class="avatar-lg">
                <img src="{{ auth()->user()->profile_image_url }}" alt="user-img" class="img-thumbnail rounded-circle" />
            </div>
        </div>
        <!--end col-->
        <div class="col">
            <div class="p-2">
                <h3 class="text-white mb-1">{{ Auth::user()->nombreUsuario }}</h3>
                <p class="text-white text-opacity-75">{{ Auth::user()->rol }}</p>
                <div class="hstack text-white-50 gap-1">
                    <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>Ubicación desconocida</div>
                </div>
            </div>
        </div>

      
        <!--end col-->
        
        <!--end col-->
    </div>
    <!--end row-->
</div>

<div class="row">
    <div class="col-lg-12">
        <div>
            <div class="tab-content pt-4 text-muted">
                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-xxl-3">
                            <div class="card ">
                                <div class="card-body text-center">
                                    <h1 class="mb-5">¡Bienvenido a FitAdmin!</h1>
                                    <p class="lead">Estamos encantados de tenerte aquí. Explora nuestras funcionalidades y comienza tu viaje con nosotros.</p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Información</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">Nombre Completo :</th>
                                                    <td class="text-muted">{{ Auth::user()->nombreUsuario }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Teléfono :</th>
                                                    <td class="text-muted">{{ Auth::user()->telefono ?? 'No registrado' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">E-mail :</th>
                                                    <td class="text-muted">{{ Auth::user()->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Ubicación :</th>
                                                    <td class="text-muted">Desconocida</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Fecha de Registro</th>
                                                    <td class="text-muted">{{ \Carbon\Carbon::parse(Auth::user()->fechaCreacion)->format('d M Y') }}</td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end tab pane -->
            </div>
        </div>
    </div>
</div>
<!-- End::row-2 -->
@endsection