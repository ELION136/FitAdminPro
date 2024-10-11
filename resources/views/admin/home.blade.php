@extends('layouts.app')
@section('content')
<div class="profile-wrapper text-center" style="padding: 30px 20px; background: rgba(255, 255, 255, 0.05); border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
    <div class="avatar position-relative mb-3">
        <img src="{{ auth()->user()->profile_image_url }}" alt="user-img" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" />
    </div>
    <div class="user-info">
        <h4 class="mb-1" style="font-size: 1.5rem; font-weight: bold;  text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);">{{ Auth::user()->nombreUsuario }}</h4>
        <p class="mb-2" style="font-size: 1rem;  letter-spacing: 0.5px;">{{ Auth::user()->rol }}</p>
    </div>
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