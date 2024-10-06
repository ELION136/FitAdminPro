@extends('layouts.admin') <!-- Asegúrate de que este es el layout correcto -->

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Reporte</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inscripciones</a></li>
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
                    <h5 class="card-title mb-0">Reportes</h5>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form method="GET" action="{{ route('admin.reportes.inscripciones') }}">
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
                                    <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa
                                    </option>
                                    <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }}>Vencida
                                    </option>
                                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                        Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="estadoPago" class="form-label">Estado de Pago</label>
                                <select name="estadoPago" class="form-select" data-plugin="choices"
                                    data-choices-search-false>
                                    <option value="">Todos</option>
                                    <option value="pendiente" {{ request('estadoPago') == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="completado"
                                        {{ request('estadoPago') == 'completado' ? 'selected' : '' }}>Completado</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="cliente_nombre" class="form-label">Cliente</label>
                                <input type="text" name="cliente_nombre" class="form-control"
                                    value="{{ request('cliente_nombre') }}" placeholder="Buscar por nombre">
                            </div>
                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="ri-equalizer-fill align-bottom me-1"></i> Filtrar</button>
                                    <a href="{{ route('admin.reportes.inscripciones') }}" class="btn btn-secondary"><i
                                            class="ri-refresh-line align-bottom me-1"></i> Limpiar Filtros</a>
                                    <a href="{{ route('admin.reportes.inscripciones.exportar.pdf', request()->all()) }}"
                                        class="btn btn-danger"><i class="ri-file-download-line align-bottom me-1"></i>
                                        Exportar PDF</a>
                                    <a href="{{ route('admin.reportes.inscripciones.exportar.excel', request()->all()) }}"
                                        class="btn btn-success"><i class="ri-file-download-line align-bottom me-1"></i>
                                        Exportar Excel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- Tabla de Resultados -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100" id="tablita">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Membresía</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Monto de Pago (Bs.)</th>
                                    <th>Estado de Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inscripciones as $inscripcion)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $inscripcion->cliente->nombre }}</td>
                                        <td>{{ $inscripcion->membresia->nombre }}</td>
                                        <td>{{ $inscripcion->fechaInicio->format('d/m/Y') }}</td>
                                        <td>{{ $inscripcion->fechaFin->format('d/m/Y') }}</td>
                                        <td>{{ ucfirst($inscripcion->estado) }}</td>
                                        <td>{{ number_format($inscripcion->montoPago, 2, ',', '.') }} Bs.</td>
                                        <td>{{ ucfirst($inscripcion->estadoPago) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right">Total:</th>
                                    <th></th> <!-- Aquí irá el total de "Monto de Pago" -->
                                    <th></th> <!-- Para el "Estado de Pago" (vacío) -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Totales Generales -->
                    <div class="row mt-4">
                        <!-- Total de Inscripciones -->
                        <div class="col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="ri-user-add-line display-5 text-primary"></i>
                                    <h5 class="card-title mt-3">Total de Inscripciones</h5>
                                    <p class="card-text fs-4"><strong>{{ $totalInscripciones }}</strong></p>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Total de Pagos Completados -->
                        <div class="col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="ri-money-dollar-circle-line display-5 text-success"></i>
                                    <h5 class="card-title mt-3">Total de Pagos Completados</h5>
                                    <p class="card-text fs-4"><strong>{{ number_format($totalPagos, 2, ',', '.') }} Bs.</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
       $(document).ready(function() {
    @if ($inscripciones->count() > 0)
    $('#tablita').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        lengthMenu: [5, 10, 25, 50, 75, 100],
        pageLength: 100,
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
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Función para convertir los valores de la columna a números
            var intVal = function(i) {
                return typeof i === 'string' ?
                    parseFloat(i.replace(/[\$,Bs.]/g, '').replace(',', '.')) :
                    typeof i === 'number' ? i : 0;
            };

            // Sumar toda la columna del monto de pago
            var total = api
                .column(6, { // Índice de la columna del Monto de Pago
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Actualizar el footer con el total formateado
            $(api.column(6).footer()).html(
                number_format(total, 2, ',', '.') + ' Bs.'
            );
        }
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
