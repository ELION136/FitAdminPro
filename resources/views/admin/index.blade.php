@extends('layouts.app')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Panel Principal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active">Incio</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row project-wrapper">
        <div class="col-xxl-8">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-2">
                                        <i data-feather="user" class="text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Usuarios</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ $totalUsuarios }}">0</span></h4>
                                        <span class="badge bg-danger-subtle text-danger fs-12"><i
                                                class="ri-arrow-down-s-line fs-13 align-middle me-1"></i>5.02 %</span>
                                    </div>
                                    <p class="text-muted text-truncate mb-0">Usuarios en el mes</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                                        <i data-feather="award" class="text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Asistencias</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="">0</span></h4>
                                        <span class="badge bg-success-subtle text-success fs-12"><i
                                                class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58 %</span>
                                    </div>
                                    <p class="text-muted mb-0">Asistencias en el mes</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->


                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle text-info rounded-2 fs-2">
                                        <i data-feather="clock" class="text-info"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Miembros</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="0">{{ $totalMembresiasActivas }}</span></h4>
                                        <span class="badge bg-danger-subtle text-danger fs-12"><i
                                                class="ri-arrow-down-s-line fs-13 align-middle me-1"></i>10.35 %</span>
                                    </div>
                                    <p class="text-muted text-truncate mb-0">en el mes month</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <!-- Tarjeta de Entrenadores Registrados -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-2">
                                        <i data-feather="user-check" class="text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Entrenadores Activos</p>
                                    <h4 class="fs-4 mb-0"><span class="counter-value"
                                            data-target="{{ $totalEntrenadores }}">0</span></h4>
                                    <p class="text-muted mb-0">Entrenadores en el sistema</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Clientes Registrados -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle text-success rounded-2 fs-2">
                                        <i data-feather="user" class="text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Clientes Activos</p>
                                    <h4 class="fs-4 mb-0"><span class="counter-value"
                                            data-target="{{ $totalClientes }}">0</span></h4>
                                    <p class="text-muted mb-0">Clientes en el sistema</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Ingresos del Mes -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                                        <i data-feather="dollar-sign" class="text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Ingresos del Mes</p>
                                    <h4 class="fs-4 mb-0">Bs.- <span class="counter-value"
                                            data-target="">0</span></h4>
                                    <p class="text-muted mb-0">Monto total recibido</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Reservas Pendientes -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle text-danger rounded-2 fs-2">
                                        <i data-feather="calendar" class="text-danger"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Reservas Pendientes</p>
                                    <h4 class="fs-4 mb-0"><span class="counter-value"
                                            data-target="">0</span></h4>
                                    <p class="text-muted mb-0">Reservas aún no procesadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Servicios Activos -->
                <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle text-info rounded-2 fs-2">
                                        <i data-feather="briefcase" class="text-info"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Servicios Activos</p>
                                    <h4 class="fs-4 mb-0"><span class="counter-value"
                                            data-target="">0</span></h4>
                                    <p class="text-muted mb-0">Servicios ofrecidos actualmente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="row">
                <!-- Gráfico de barras: Usuarios por Rol -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Usuarios por Rol</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="usuariosPorRol"></canvas>
                        </div>
                    </div>
                </div>
            
                <!-- Gráfico de líneas: Asistencias por Mes -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Asistencias por Mes</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="asistenciasPorMes"></canvas>
                        </div>
                    </div>
                </div>
            
                <!-- Gráfico de pasteles: Estado de las Membresías Activas -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Estado de las Membresías</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="estadoMembresias"></canvas>
                        </div>
                    </div>
                </div>
            
                <!-- Gráficos con ApexCharts -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Asistencias por Semana (ApexCharts)</h4>
                        </div>
                        <div class="card-body">
                            <div id="line_chart_basic" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Membresías Activas por Mes (ApexCharts)</h4>
                        </div>
                        <div class="card-body">
                            <div id="line_chart_zoomable" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>



        </div><!-- end row -->
    </div>
@endsection
<!-- end row -->
