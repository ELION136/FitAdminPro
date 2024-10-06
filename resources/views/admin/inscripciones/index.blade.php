@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Historial</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inscripciones</a></li>
                        <li class="breadcrumb-item active">Historial</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas con cantidad de cada tipo de membresía -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-bar-chart-fill me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Total Membresías Activas</h5>
                            <h3>{{ $membresiasActivas }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-close-circle-fill me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title " >Total Membresías Vencidas</h5>
                            <h3>{{ $membresiasVencidas }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-time-fill me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Membresías Pendientes de Pago</h5>
                            <h3>{{ $membresiasPendientesPago }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Filtros -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Miembros</h5>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form method="GET" action="{{ route('admin.inscripciones.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-sm-3">
                                <label for="fecha_inicio">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-sm-3">
                                <label for="fecha_fin">Fecha de Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-sm-3">
                                <label for="estado">Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa</option>
                                    <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }}>Vencida</option>
                                    <option value="pendiente de pago" {{ request('estado') == 'pendiente de pago' ? 'selected' : '' }}>Pendiente de Pago</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="estadoPago">Estado de Pago</label>
                                <select name="estadoPago" class="form-control">
                                    <option value="">Todos los pagos</option>
                                    <option value="completado" {{ request('estadoPago') == 'completado' ? 'selected' : '' }}>Completado</option>
                                    <option value="pendiente" {{ request('estadoPago') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="fallido" {{ request('estadoPago') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                                </select>
                            </div>
                            <!-- Botón para aplicar filtros -->
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-equalizer-fill me-2 align-bottom"></i> Aplicar Filtros
                                </button>
                            </div>
                            <!-- Botón para limpiar filtros -->
                            <div class="col-sm-3">
                                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary w-100">
                                    <i class="ri-refresh-fill me-2 align-bottom"></i> Limpiar Filtros
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabla -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100" id="repoinsTable">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Membresía</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Estado Pago</th>
                                    <th>Monto Pagado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @foreach ($inscripciones as $inscripcion)
                                    <tr>
                                        <td>{{ $inscripcion->cliente->nombre }} {{ $inscripcion->cliente->primerApellido }}</td>
                                        <td>{{ $inscripcion->membresia->nombre }}</td>
                                        <td>{{ $inscripcion->fechaInicio->format('d/m/Y') }}</td>
                                        <td>{{ $inscripcion->fechaFin->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge text-{{ $inscripcion->estado == 'activa' ? 'info' : ($inscripcion->estado == 'vencida' ? 'danger' : 'warning') }} fw-bold">
                                                {{ ucfirst($inscripcion->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge text-{{ $inscripcion->estadoPago == 'completado' ? 'info' : ($inscripcion->estadoPago == 'pendiente' ? 'warning' : 'danger') }} fw-bold">
                                                {{ ucfirst($inscripcion->estadoPago) }}
                                            </span>
                                        </td>
                                        
                                        <td>{{ number_format($inscripcion->montoPago, 2) }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.inscripciones.updateEstado', $inscripcion->idInscripcion) }}" style="display:inline;">
                                                @csrf
                                                <select name="estado" onchange="this.form.submit()" class="form-select form-select-sm">
                                                    <option value="activa" {{ $inscripcion->estado == 'activa' ? 'selected' : '' }}>Activa</option>
                                                    <option value="vencida" {{ $inscripcion->estado == 'vencida' ? 'selected' : '' }}>Vencida</option>
                                                    <option value="pendiente de pago" {{ $inscripcion->estado == 'pendiente de pago' ? 'selected' : '' }}>Pendiente de Pago</option>
                                                </select>
                                            </form>
                                            
                                            <!-- Botón para editar el estado de pago -->
                                            <form method="POST" action="{{ route('admin.inscripciones.updateEstadoPago', $inscripcion->idInscripcion) }}" style="display:inline;">
                                                @csrf
                                                <select name="estadoPago" onchange="this.form.submit()" class="form-select form-select-sm">
                                                    <option value="completado" {{ $inscripcion->estadoPago == 'completado' ? 'selected' : '' }}>Completado</option>
                                                    <option value="pendiente" {{ $inscripcion->estadoPago == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="fallido" {{ $inscripcion->estadoPago == 'fallido' ? 'selected' : '' }}>Fallido</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#repoinsTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 10,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    decimal: "",
                    emptyTable: "No hay datos disponibles en la tabla",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                    infoFiltered: "(filtrado de _MAX_ entradas totales)",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "No se encontraron registros coincidentes",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    aria: {
                        sortAscending: ": activar para ordenar la columna de manera ascendente",
                        sortDescending: ": activar para ordenar la columna de manera descendente"
                    }
                },
            });
        });
    </script>
@endpush
