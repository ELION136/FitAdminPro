@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Reporte</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reservas</a></li>
                        <li class="breadcrumb-item active">Reporte</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Reportes de Reservas</h5>
                </div>

                <div class="card-body border-bottom-dashed border-bottom">
                    <form method="GET" action="{{ route('admin.reportes.reservas') }}">
                        <div class="row g-4">
                            <div class="col-md-2">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                    value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="estado" class="form-label">Estado</label>
                                <select name="estado" class="form-select" data-plugin="choices" data-choices-search-false>
                                    <option value="">Todos</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente
                                    </option>
                                    <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado
                                    </option>
                                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>
                                        Cancelado
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="nombre_cliente" class="form-label">Nombre del cliente:</label>
                                <input type="text" id="nombre_cliente" class="form-control" name="nombre_cliente"
                                    placeholder="Nombre del cliente">
                            </div>
                            <div class="col-md-2">
                                <label for="servicio_mas_reservado" class="form-label">Servicio</label>
                                <input type="text" name="servicio_mas_reservado" class="form-control"
                                    value="{{ request('servicio_mas_reservado') }}" placeholder="Buscar por servicio">
                            </div>
                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-equalizer-fill align-bottom me-1"></i> Filtrar
                                    </button>
                                    <a href="{{ route('admin.reportes.reservas') }}" class="btn btn-secondary">
                                        <i class="ri-refresh-line align-bottom me-1"></i> Limpiar Filtros
                                    </a>
                                    <a href="{{ route('admin.reportes.reservas.exportarPDF') }}" class="btn btn-danger">
                                        <i class="ri-file-download-line align-bottom me-1"></i> Exportar PDF
                                    </a>
                                    <a href="{{ route('admin.reportes.reservas.exportarExcel') }}" class="btn btn-success">
                                        <i class="ri-file-download-line align-bottom me-1"></i> Exportar Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100" id="tablita">
                            <thead>

                                <tr>
                                    <th>Cliente</th>
                                    <th>Fecha de Reserva</th>
                                    <th>Servicio</th>
                                    <th>Entrenador</th>
                                    <th>Hora de Inicio</th>
                                    <th>Hora de Fin</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $reserva)
                                    @foreach ($reserva->detalleReservas as $detalle)
                                        <tr>
                                            <td>{{ $reserva->cliente->nombre }}</td>
                                            <td>{{ $reserva->fechaReserva }}</td>
                                            <td>{{ $detalle->horario->servicio->nombre }}</td>
                                            <td>{{ $detalle->horario->entrenador->nombre }}</td>
                                            <td>{{ $detalle->horario->horaInicio }}</td>
                                            <td>{{ $detalle->horario->horaFin }}</td>
                                            <td>{{ number_format( $reserva->total , 2, ',', '.') }} Bs.</td>
                                            <td>{{ $reserva->estado }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <!-- Total de Reservas -->
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-user-add-line display-5 text-primary"></i>
                    <h5 class="card-title mt-3">Total de Reservas</h5>
                    <p class="card-text fs-4"><strong>{{ $totalReservas}}</strong></p>
                </div>
            </div>
        </div>
    
        <!-- Total de Pagos Completados -->
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-money-dollar-circle-line display-5 text-success"></i>
                    <h5 class="card-title mt-3">Total de Pagos Completados</h5>
                    <p class="card-text fs-4"><strong>{{ number_format($totalPagosCompletados, 2, ',', '.') }} Bs.</strong></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Total de Pagos Pendientes -->
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-time-line display-5 text-warning"></i>
                    <h5 class="card-title mt-3">Total de Pagos Pendientes</h5>
                    <p class="card-text fs-4"><strong>{{ number_format($totalPagosPendientes, 2, ',', '.') }} Bs.</strong></p>
                </div>
            </div>
        </div>
    
        <!-- Total de Pagos Cancelados -->
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="ri-close-circle-line display-5 text-danger"></i>
                    <h5 class="card-title mt-3">Total de Pagos Cancelados</h5>
                    <p class="card-text fs-4"><strong>{{ number_format($totalPagosCancelados, 2, ',', '.') }} Bs.</strong></p>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            @if ($reservas->count() > 0)
                $('#tablita').DataTable({
                    dom: 'Bfrtip',
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50, 75, 100],
                    pageLength: 5,
                    buttons: [{
                            extend: 'copyHtml5',
                            text: '<i class="fas fa-copy"></i> Copiar',
                            titleAttr: 'Copiar al portapapeles',
                            className: 'btn btn-primary'
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            titleAttr: 'Exportar a CSV',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            titleAttr: 'Exportar a Excel',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Imprimir',
                            titleAttr: 'Imprimir',
                            className: 'btn btn-info'
                        }
                    ],
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
            @endif
        });

        // Función para formatear números
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = number.toFixed(decimals);
            var parts = number.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);
        }
    </script>
@endpush
