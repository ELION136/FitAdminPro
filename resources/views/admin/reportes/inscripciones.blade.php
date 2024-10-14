@extends('layouts.app') <!-- Asegúrate de que este es el layout correcto -->

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
                                    <a href="{{ route('admin.reportes.inscripcionesPDF', request()->all()) }}"
                                        class="btn btn-danger"><i class="ri-file-download-line align-bottom me-1"></i>
                                        Exportar PDF</a>
                                    <a href="{{ route('admin.reportes.inscripcionesExcel', request()->all()) }}"
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
                        <table class="table table-bordered text-nowrap w-100" id="reservaTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>tipo</th>
                                    <th>Fecha de inscripcion</th>
                                    <th>Estado</th>
                                    <th>Monto de Pago (Bs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inscripciones as $inscripcion)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <!-- Verifica si el cliente existe antes de acceder a sus propiedades -->
                                    <td>{{ $inscripcion->cliente ? $inscripcion->cliente->nombre : 'Sin cliente' }}</td>

                                    <!-- Acceder a la membresía a través de detalleInscripciones -->
                                    <td>
                                        @php
                                            $detalleMembresia = $inscripcion->detalleInscripciones->where('tipoProducto', 'membresia')->first();
                                        @endphp
                                        {{ $detalleMembresia && $detalleMembresia->membresia ? $detalleMembresia->membresia->nombre : 'Sin membresía' }}
                                    </td>

                                    <!-- Fecha de inscripción -->
                                    <td>{{ $inscripcion->fechaInscripcion ? \Carbon\Carbon::parse($inscripcion->fechaInscripcion)->format('d/m/Y') : 'Sin fecha' }}</td>

                                    <td>{{ ucfirst($inscripcion->estado) }}</td>
                                    <td>{{ number_format($inscripcion->totalPago, 2, ',', '.') }} Bs.</td>
                                </tr>
                                @endforeach
                            </tbody>
        
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

                        <!-- Total de Ingresos -->
                        <div class="col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="ri-money-dollar-circle-line display-5 text-success"></i>
                                    <h5 class="card-title mt-3">Total de Ingresos</h5>
                                    <p class="card-text fs-4"><strong>{{ number_format($totalIngresos, 2, ',', '.') }}
                                            Bs.</strong></p>
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
            $('#reservaTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // Botón de exportar a Excel
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Exportar a Excel',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                header: function (data, columnIdx) {
                                    return data.toUpperCase();
                                }
                            }
                        },
                        filename: 'Nombre_del_archivo_excel',
                    },
                    // Botón de exportar a PDF (ajustado)
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: ':visible'
                        },
                        filename: 'Nombre_del_archivo_pdf',
                        customize: function (doc) {
                            // Establecer la fuente a 'Helvetica'
                            doc.defaultStyle.font = 'Helvetica';

                            // Personalizar el PDF (opcional)
                            doc.content.splice(0, 0, {
                                text: 'Título personalizado',
                                fontSize: 14,
                                alignment: 'center',
                                margin: [0, 0, 0, 20]
                            });

                            doc.defaultStyle.fontSize = 10;

                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        {
                                            alignment: 'left',
                                            text: ['Fecha de creación: ', { text: new Date().toLocaleDateString() }]
                                        },
                                        {
                                            alignment: 'right',
                                            text: ['Página ', { text: page.toString() },  ' de ', { text: pages.toString() }]
                                        }
                                    ],
                                    margin: [10, 0]
                                }
                            });
                        }
                    },
                    // Botón de imprimir
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Imprimir',
                        titleAttr: 'Imprimir',
                        className: 'btn btn-secondary',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5,
                language: {
                    // Configuración de idioma
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
                    },
                    buttons: {
                        copyTitle: 'Copiado al portapapeles',
                        copySuccess: {
                            _: '%d líneas copiadas',
                            1: '1 línea copiada'
                        }
                    }
                },
            });
        });
    </script>
@endpush
