@extends('layouts.admin')

@section('content')
    <div class="row ">
        <!-- Membresía Activa -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle text-success rounded-2 fs-2">
                            <i data-feather="check-circle" class="text-success"></i>
                        </span>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Membresía</p>
                        <h4 class="mb-2">
                            {{ $inscripcionActiva ? 'Activa' : 'Inactiva' }}
                        </h4>
                        @if ($inscripcionActiva)
                            <p class="text-muted mb-0">
                                Desde {{ $inscripcionActiva->fechaInicio }} hasta {{ $inscripcionActiva->fechaFin }}
                            </p>
                        @else
                            <p class="text-muted mb-0">Sin membresía activa</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Asistencias del Mes -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                            <i data-feather="calendar" class="text-warning"></i>
                        </span>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Asistencias del Mes</p>
                        <h4 class="mb-2">{{ $asistenciasMes }}</h4>
                        <span class="badge bg-success-subtle text-success fs-12">
                            <i class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>{{ $porcentajeMes }} %
                        </span>
                        <p class="text-muted mb-0">Asistencias registradas este mes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asistencias del Año -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle text-info rounded-2 fs-2">
                            <i data-feather="clock" class="text-info"></i>
                        </span>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Asistencias del Año</p>
                        <h4 class="mb-2">{{ $asistenciasAnio }}</h4>
                        <span class="badge bg-danger-subtle text-danger fs-12">
                            <i class="ri-arrow-down-s-line fs-13 align-middle me-1"></i>{{ $porcentajeAnio }} %
                        </span>
                        <p class="text-muted mb-0">Asistencias registradas este año</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-xxl-4">
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title mb-0">Asistencias del Cliente</h4>
            </div><!-- end cardheader -->
            <div class="card-body pt-0">
                <div id="calendar"></div> <!-- Contenedor para el calendario -->
            </div><!-- end cardbody -->
        </div><!-- end card -->
    </div><!-- end col -->
    </div>

    <!-- Tabla de Servicios -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Servicios Ofrecidos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="small">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Duración</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>{{ $servicio->descripcion }}</td>
                                        <td>{{ $servicio->duracion }} min</td>
                                        <td>{{ $servicio->precio }} Bs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Entrenadores -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Entrenadores</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm table-nowrap table-centered align-middle">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th>Edad</th>
                                    <th>Género</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach ($entrenadores as $entrenador)
                                    <tr>
                                        <td>
                                            @if ($entrenador->usuario->image)
                                                <img src="{{ asset('storage/' . $entrenador->usuario->image) }}"
                                                    alt="Foto de perfil" width="50" height="50">
                                            @else
                                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil"
                                                    width="25" height="25">
                                            @endif
                                        </td>
                                        <td>{{ $entrenador->nombre }}</td>
                                        <td>{{ $entrenador->especialidad }}</td>
                                        <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->age }} años</td>
                                        <td>{{ $entrenador->genero }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
        
        <!-- Scripts para los gráficos -->
        
    @endsection
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
        
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '{{ route('cliente.asistencias') }}', // Ruta para obtener las asistencias
                eventColor: '#1abc9c', // Color de las asistencias en el calendario
                locale: 'es', // Cambia a español
            });
        
            calendar.render();
        });
        </script>
        
    @endpush
