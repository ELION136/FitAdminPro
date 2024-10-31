@extends('layouts.app')

@section('content')
    <!-- Título y breadcrumbs -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Historial</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Inscripciones</a></li>
                        <li class="breadcrumb-item active">Historial</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <!-- Tarjeta de Membresías -->
        <div class="col-xl-2 col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body p-2">
                    <i class="ri-bar-chart-fill align-bottom"></i>
                    <small>Total Membresías:</small> {{ $totalMembresias }}
                </div>
            </div>
        </div>
    
        <!-- Tarjeta de Servicios -->
        <div class="col-xl-2 col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body p-2">
                    <i class="ri-service-fill align-bottom"></i>
                    <small>Total Servicios:</small> {{ $totalServicios }}
                </div>
            </div>
        </div>
    
        <!-- Tarjeta de Activas -->
        <div class="col-xl-2 col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body p-2">
                    <i class="ri-check-line align-bottom"></i>
                    <small>Activas:</small> {{ $totalActivas }}
                </div>
            </div>
        </div>
    
        <!-- Tarjeta de Vencidas -->
        <div class="col-xl-2 col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body p-2">
                    <i class="ri-close-line align-bottom"></i>
                    <small>Vencidas:</small> {{ $totalVencidas }}
                </div>
            </div>
        </div>
    
        <!-- Tarjeta de Canceladas -->
        <div class="col-xl-2 col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body p-2">
                    <i class="ri-forbid-line align-bottom"></i>
                    <small>Canceladas:</small> {{ $totalCanceladas }}
                </div>
            </div>
        </div>
    </div>
    

    <!-- Pestañas para membresías y servicios -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Inscripciones</h5>
                    <div class="mb-3">
                        <a href="{{ route('admin.inscripciones.create') }}" class="btn btn-success">
                            <i class="ri-add-line me-1"></i> Nueva Inscripción
                        </a>
                    </div>
                </div>

                <div class="card-body border-bottom-dashed border-bottom">
                    <!-- Formulario de filtros -->
                    <form method="GET" action="{{ route('admin.inscripciones.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="fecha_inicio">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_fin">Fecha de Fin</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                    value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="estado">Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa
                                    </option>
                                    <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }}>
                                        Vencida
                                    </option>
                                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                        Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-equalizer-fill me-2 align-bottom"></i> Aplicar Filtros
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary w-100">
                                    <i class="ri-refresh-fill me-2 align-bottom"></i> Limpiar Filtros
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Pestañas -->
                <div class="card-body">
                    <ul class="nav nav-tabs" id="inscripcionesTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="membresias-tab" data-bs-toggle="tab" href="#membresias"
                                role="tab" aria-controls="membresias" aria-selected="true">Membresías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="servicios-tab" data-bs-toggle="tab" href="#servicios" role="tab"
                                aria-controls="servicios" aria-selected="false">Servicios</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="inscripcionesTabContent">
                        <!-- Tab de Membresías -->
                        <div class="tab-pane fade show active" id="membresias" role="tabpanel"
                            aria-labelledby="membresias-tab">
                            <!-- Tabla de Membresías -->
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered text-nowrap w-100" id="membresiasTable">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Membresía</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Estado</th>
                                            <th>Código QR</th>
                                            <th>Monto Pagado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($inscripcionesMembresias as $inscripcion)
                                            <tr>
                                                <td>{{ $inscripcion->cliente->nombre }}
                                                    {{ $inscripcion->cliente->primerApellido }}</td>
                                                <td>{{ $inscripcion->producto }}</td>
                                                <td>{{ $inscripcion->fechaInicio ? $inscripcion->fechaInicio->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $inscripcion->fechaFin ? $inscripcion->fechaFin->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge text-{{ $inscripcion->estado == 'activa' ? 'info' : ($inscripcion->estado == 'vencida' ? 'danger' : 'warning') }} fw-bold">
                                                        {{ ucfirst($inscripcion->estado) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($inscripcion->cliente->qrCode)
                                                        <img src="{{ asset('storage/' . $inscripcion->cliente->qrCode) }}"
                                                            alt="Código QR de cliente" width="100">
                                                    @else
                                                        <p>Sin QR generado</p>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($inscripcion->montoPago, 2) }}</td>
                                                <td>
                                                    <!-- Botón para ver detalle (modal) -->


                                                    <!-- Botón para imprimir comprobante -->
                                                    <a href="javascript:void(0);"
                                                        onclick="abrirVentanaPDF({{ $inscripcion->idInscripcion }})"
                                                        class="btn btn-info btn-sm" title="Imprimir Comprobante">
                                                        <i class="ri-printer-line"></i>
                                                    </a>

                                                    <!-- Botón para anular venta -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                    <form action="{{ route('admin.inscripciones.cancelar', $inscripcion->idInscripcion) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT') <!-- Esto simula el método PUT en el formulario -->
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Anular Venta" onclick="return confirm('¿Está seguro de anular esta venta?')">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @endif

                                                    <!-- Botón para generar credencial y enviar por WhatsApp -->
                                                    @if ($inscripcion->estado == 'activa')
                                                        <a href="{{ route('admin.inscripciones.generarQr', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-primary btn-sm" title="Generar QR">
                                                            <i class="ri-qr-code-line"></i>
                                                        </a>

                                                        
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Tab de Servicios -->
                        <div class="tab-pane fade" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">
                            <!-- Tabla de Servicios -->
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered text-nowrap w-100" id="serviciosTable">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Servicio</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Estado</th>
                                            <th>Monto Pagado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($inscripcionesServicios as $inscripcion)
                                            <tr>
                                                <td>{{ $inscripcion->cliente->nombre }}
                                                    {{ $inscripcion->cliente->primerApellido }}</td>

                                                @php
                                                    // Verificar si la relación detallesInscripciones está cargada y no es null
                                                    $detalleServicio = $inscripcion->detallesInscripciones
                                                        ? $inscripcion->detallesInscripciones->firstWhere(
                                                            'tipoProducto',
                                                            'servicio',
                                                        )
                                                        : null;
                                                @endphp

                                                <td>{{ $detalleServicio && $detalleServicio->servicio ? $detalleServicio->servicio->nombre : 'Servicio no disponible' }}
                                                </td>

                                                <td>
                                                    @if ($detalleServicio && $detalleServicio->servicio)
                                                        {{ $detalleServicio->servicio->fechaInicio ? \Carbon\Carbon::parse($detalleServicio->servicio->fechaInicio)->format('d/m/Y') : 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($detalleServicio && $detalleServicio->servicio)
                                                        {{ $detalleServicio->servicio->fechaFin ? \Carbon\Carbon::parse($detalleServicio->servicio->fechaFin)->format('d/m/Y') : 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <td>
                                                    <span
                                                        class="badge text-{{ $inscripcion->estado == 'activa' ? 'info' : ($inscripcion->estado == 'vencida' ? 'danger' : 'warning') }} fw-bold">
                                                        {{ ucfirst($inscripcion->estado) }}
                                                    </span>
                                                </td>

                                                <td>{{ number_format($inscripcion->totalPago, 2) }}</td>

                                                <td>
                                                    <!-- Botones de acciones -->

                                                    <!-- Botón para imprimir comprobante, con el color cambiado -->
                                                    <a href="javascript:void(0);"
                                                        onclick="abrirVentanaPDF({{ $inscripcion->idInscripcion }})"
                                                        class="btn btn-info btn-sm" title="Imprimir Comprobante">
                                                        <i class="ri-printer-line"></i>
                                                    </a>


                                                    <!-- Botón para anular venta -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                    <form action="{{ route('admin.inscripciones.cancelar', $inscripcion->idInscripcion) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT') <!-- Esto simula una solicitud PUT -->
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Anular Venta" onclick="return confirm('¿Está seguro de anular esta venta?')">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @endif

                                                    
                                                    

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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar DataTables para cada tabla
            $('#membresiasTable').DataTable({
                responsive: false,
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

            $('#serviciosTable').DataTable({
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

        function abrirVentanaPDF(idInscripcion) {
            // Generar la URL de la ruta usando el ID de la inscripción
            const url = new URL('{{ route('admin.inscripciones.comprobante', ':id') }}'.replace(':id', idInscripcion),
                window.location.origin);

            // Abrir la URL en una nueva ventana con el tamaño especificado
            window.open(url, '_blank', 'width=800,height=600');
        }
    </script>
@endpush
