@extends('layouts.admin')
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

    <!-- Mensaje de alerta -->
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
        <div class="col-md-4">
            <div class="card bg-info mb-3">
                <div class="card-header">Total de Asistencias</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $asistencias->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card  bg-success mb-3">
                <div class="card-header">Asistencias de Hoy</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $asistencias->where('fecha', now()->format('Y-m-d'))->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning mb-3">
                <div class="card-header">Clientes sin Hora de Salida</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $asistencias->whereNull('horaSalida')->count() }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Miembros</h5>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form method="GET" action="{{ route('admin.asistencias.index') }}" class="mb-4">
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
                        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                    </form>
                </div>

                <!-- Tabla de asistencias -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservaTable" class="table table-hover table-striped table-bordered w-100">
                            <thead >
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Hora Entrada</th>
                                    <th>Hora Salida</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $asistencia)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $asistencia->cliente->nombre }}</td>
                                        <td>{{ $asistencia->fecha }}</td>
                                        <td>{{ $asistencia->horaEntrada }}</td>
                                        <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
                                        <td>
                                            <!-- Botón para abrir modal de edición -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editAsistenciaModal{{ $asistencia->idAsistencia }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>

                                            <!-- Modal de edición -->
                                            <div class="modal fade" id="editAsistenciaModal{{ $asistencia->idAsistencia }}"
                                                tabindex="-1" aria-labelledby="editAsistenciaModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editAsistenciaModalLabel">Editar
                                                                Asistencia</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST"
                                                            action="{{ route('admin.asistencias.update', $asistencia->idAsistencia) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="horaEntrada" class="form-label">Hora
                                                                        Entrada</label>
                                                                    <input type="time" name="horaEntrada"
                                                                        class="form-control"
                                                                        value="{{ $asistencia->horaEntrada }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="horaSalida" class="form-label">Hora
                                                                        Salida</label>
                                                                    <input type="time" name="horaSalida"
                                                                        class="form-control"
                                                                        value="{{ $asistencia->horaSalida }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar
                                                                    Cambios</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
            $('#reservaTable').DataTable({
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
