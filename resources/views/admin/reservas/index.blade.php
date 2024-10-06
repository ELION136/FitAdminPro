@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Lista de Reservas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- Estadísticas de Reservas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Reservas</h5>
                    <p class="card-text">{{ $totalReservas }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Reservas Pagadas</h5>
                    <p class="card-text">{{ $reservasPagadas }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Reservas Pendientes</h5>
                    <p class="card-text">{{ $reservasPendientes }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Mejorados -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Miembros</h5>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form method="GET" action="{{ route('admin.reservas.index') }}" class="mb-4">
                        <div class="row g-3">
                            <!-- Filtro por Estado -->
                            <div class="col-sm-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" name="estado">
                                    <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos
                                    </option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado
                                    </option>
                                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>
                                        Cancelado</option>
                                </select>
                            </div>

                            <!-- Filtro por Cliente -->
                            <div class="col-sm-3">
                                <label for="cliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" name="cliente" value="{{ request('cliente') }}"
                                    placeholder="Nombre del cliente">
                            </div>

                            <!-- Filtro por Fecha Inicio -->
                            <div class="col-sm-3">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio"
                                    value="{{ request('fecha_inicio') }}">
                            </div>

                            <!-- Filtro por Fecha Fin -->
                            <div class="col-sm-3">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin"
                                    value="{{ request('fecha_fin') }}">
                            </div>

                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-equalizer-fill me-2 align-bottom"></i> Aplicar Filtros
                                </button>
                            </div>
                            <!-- Botón para limpiar filtros -->
                            <div class="col-sm-3">
                                <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary w-100">
                                    <i class="ri-refresh-fill me-2 align-bottom"></i> Limpiar Filtros
                                </a>
                            </div>
                            <div class="col-sm-3">
                                <a href="{{ route('admin.reservas.create') }}" class="btn btn-success w-100">
                                    <i class="ri-refresh-fill me-2 align-bottom"></i> Añadir Reserva
                                </a>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- Tabla de Reservas -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservaTable" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Fecha de Reserva</th>
                                    <th>Total (bs)</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $reserva)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $reserva->cliente->nombre }} {{ $reserva->cliente->primerApellido }}</td>
                                        <td>
                                            @foreach ($reserva->detalleReservas as $detalle)
                                                {{ $detalle->horario->servicio->nombre }} <br>
                                            @endforeach
                                        </td>
                                        <td>{{ $reserva->fechaReserva }}</td>
                                        <td>Bs {{ number_format($reserva->total, 2, '.', ',') }}</td>
                                        <td>
                                            <span
                                                class="{{ $reserva->estado == 'pagado' ? 'text-success' : ($reserva->estado == 'pendiente' ? 'text-warning' : 'text-danger') }}">
                                                {{ ucfirst($reserva->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <!-- Formulario para actualizar el estado -->
                                            <form
                                                action="{{ route('admin.reservas.actualizarEstado', $reserva->idReserva) }}"
                                                method="POST" id="form-estado-{{ $reserva->idReserva }}">
                                                @csrf
                                                <select name="estado" class="form-select"
                                                    onchange="document.getElementById('form-estado-{{ $reserva->idReserva }}').submit()">
                                                    <option value="pendiente"
                                                        {{ $reserva->estado == 'pendiente' ? 'selected' : '' }}>Pendiente
                                                    </option>
                                                    <option value="pagado"
                                                        {{ $reserva->estado == 'pagado' ? 'selected' : '' }}>
                                                        Pagado</option>
                                                    <option value="cancelado"
                                                        {{ $reserva->estado == 'cancelado' ? 'selected' : '' }}>Cancelado
                                                    </option>
                                                </select>
                                            </form>

                                            <!-- Formulario para cancelar la reserva -->
                                            <form action="{{ route('admin.reservas.cancelar', $reserva->idReserva) }}"
                                                method="POST" style="margin-top: 5px;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">Cancelar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (count($reservas) == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">No hay reservas disponibles</td>
                                    </tr>
                                @endif
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

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        @if (session('success'))
            Swal.fire({
                title: 'Éxito',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        document.querySelectorAll('.show_confirm').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                })
            });
        });
    </script>
@endpush
