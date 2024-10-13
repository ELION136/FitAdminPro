@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Lista de Asistencias</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Asistencias</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de alerta -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Estadísticas en tarjetas -->
    <div class="row mt-4">
        <!-- Tarjeta Total de Asistencias -->
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-check-line me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title mb-1">Total de Asistencias</h5>
                            <h3>{{ $asistencias->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Tarjeta Asistencias de Hoy -->
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-calendar-line me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title mb-1">Asistencias de Hoy</h5>
                            <h3>{{ $asistenciasHoy }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
        <!-- Tarjeta Clientes sin Asistencia -->
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-user-line me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title mb-1">Clientes sin Asistencia</h5>
                            <h3>0</h3> <!-- Cambia por la variable adecuada -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="ri-close-circle-fill me-2" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="card-title mb-1">Anuladas</h6>
                            <h3>0</h3> <!-- Cambia por la variable adecuada -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Tabla de asistencias -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Asistencias</h5>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form method="GET" action="{{ route('admin.asistencias.ver') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select name="cliente_id" class="form-select">
                                    <option value="">Todos los clientes</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->idCliente }}"
                                            {{ request('cliente_id') == $cliente->idCliente ? 'selected' : '' }}>
                                            {{ $cliente->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                                <input type="date" name="fechaInicio" class="form-control"
                                    value="{{ request('fechaInicio', $fechaInicio) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="fechaFin" class="form-label">Fecha Fin</label>
                                <input type="date" name="fechaFin" class="form-control"
                                    value="{{ request('fechaFin', $fechaFin) }}">
                            </div>
                        </div>
                
                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-equalizer-fill me-2 align-bottom"></i> Aplicar Filtros
                                </button>
                            </div>
                
                            <div class="col-sm-4">
                                <a href="{{ route('admin.asistencias.ver') }}" class="btn btn-secondary w-100">
                                    <i class="ri-refresh-fill me-2 align-bottom"></i> Limpiar Filtros
                                </a>
                            </div>
                
                            <div class="col-sm-4">
                                <a href="{{ route('admin.asistencias.index') }}" class="btn btn-success w-100">
                                    <i class="ri-add-line me-2 align-bottom"></i> Registrar Nueva Asistencia
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="asistenciasTable" class="table table-hover table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Método de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $asistencia)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $asistencia->cliente->nombre }}</td>
                                        <td>{{ $asistencia->fechaAsistencia }}</td>
                                        <td>{{ ucfirst($asistencia->metodoRegistro) }}</td>
                                        <td>
                                            <!-- Botón para ver detalles o anular asistencia -->
                                            
                                            <form method="POST" action="{{ route('admin.asistencias.anular', $asistencia->idAsistencia) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de anular esta asistencia?')">
                                                    <i class="fas fa-trash"></i> Anular
                                                </button>
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
            $('#asistenciasTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5,
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
