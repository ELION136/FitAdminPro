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

    <!-- Tarjetas con contadores -->
    <div class="row mb-4">
        <!-- Tarjeta de Membresías -->
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-bar-chart-fill me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Total Membresías</h5>
                            <h3>{{ $totalMembresias }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tarjeta de Servicios -->
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-service-fill me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Total Servicios</h5>
                            <h3>{{ $totalServicios }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tarjeta de Inscripciones Activas -->
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-check-line me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Activas</h5>
                            <h3>{{ $totalActivas }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tarjeta de Inscripciones Vencidas -->
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-close-line me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Vencidas</h5>
                            <h3>{{ $totalVencidas }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tarjeta de Inscripciones Canceladas -->
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ri-forbid-line me-2" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="card-title">Canceladas</h5>
                            <h3>{{ $totalCanceladas }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para nueva inscripción -->
    <div class="mb-3">
        <a href="#" class="btn btn-success">
            <i class="ri-add-line me-1"></i> Nueva Inscripción
        </a>
    </div>

    <!-- Pestañas para membresías y servicios -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Inscripciones</h5>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <!-- Formulario de filtros -->
                    <form method="GET" action="{{ route('admin.inscripciones.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="row g-3">
                                <div class="col-sm-3">
                                    <label for="fecha_inicio">Fecha de Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control"
                                        value="{{ request('fecha_inicio') }}">
                                </div>
                                <div class="col-sm-3">
                                    <label for="fecha_fin">Fecha de Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control"
                                        value="{{ request('fecha_fin') }}">
                                </div>
                                <div class="col-sm-3">
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
                        </div>
                    </form>
                </div>

                <!-- Pestañas -->
                <div class="card-body">
                    <ul class="nav nav-tabs" id="inscripcionesTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="membresias-tab" data-toggle="tab" href="#membresias"
                                role="tab" aria-controls="membresias" aria-selected="true">Membresías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="servicios-tab" data-toggle="tab" href="#servicios" role="tab"
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
                                                <td>{{ number_format($inscripcion->montoPago, 2) }}</td>
                                                <td>
                                                    <!-- Botón para ver detalle (modal) -->
                                                    <button class="btn btn-info btn-sm btn-detalle"
                                                        data-id="{{ $inscripcion->idInscripcion }}" title="Ver Detalle">
                                                        <i class="ri-eye-line"></i>
                                                    </button>

                                                    <!-- Botón para imprimir comprobante (no funcional) -->
                                                    <button class="btn btn-secondary btn-sm" title="Imprimir Comprobante">
                                                        <i class="ri-printer-line"></i>
                                                    </button>

                                                    <!-- Botón para anular venta -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                        <form
                                                            action="{{ route('admin.inscripciones.cancelar', $inscripcion->idInscripcion) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Anular Venta"
                                                                onclick="return confirm('¿Está seguro de anular esta venta?')">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @php
                                                        $esMembresia = $inscripcion->detalleInscripciones
                                                            ->where('tipoProducto', 'membresia')
                                                            ->isNotEmpty();
                                                        $esServicio = $inscripcion->detalleInscripciones
                                                            ->where('tipoProducto', 'servicio')
                                                            ->isNotEmpty();
                                                    @endphp

                                                    @if ($esMembresia && $inscripcion->estado == 'activa')
                                                        <!-- Botón para generar credencial -->
                                                        <a href="{{ route('admin.inscripciones.generarCredencial', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-primary btn-sm" title="Generar Credencial">
                                                            <i class="ri-qr-code-line"></i>
                                                        </a>

                                                        <!-- Botón para enviar QR por WhatsApp -->
                                                        <a href="{{ route('admin.inscripciones.enviarWhatsapp', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-success btn-sm" title="Enviar QR por WhatsApp">
                                                            <i class="ri-whatsapp-line"></i>
                                                        </a>
                                                    @endif

                                                    @if ($esServicio)
                                                        <!-- Botón para generar pase de entrada -->
                                                        <a href="{{ route('admin.inscripciones.generarPase', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-warning btn-sm"
                                                            title="Generar Pase de Entrada">
                                                            <i class="ri-ticket-line"></i>
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
                                            <th>Fecha</th>
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
                                                <td>{{ $inscripcion->producto }}</td>
                                                <td>{{ $inscripcion->fechaInicio ? $inscripcion->fechaInicio->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge text-{{ $inscripcion->estado == 'activa' ? 'info' : ($inscripcion->estado == 'vencida' ? 'danger' : 'warning') }} fw-bold">
                                                        {{ ucfirst($inscripcion->estado) }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($inscripcion->montoPago, 2) }}</td>
                                                <td>
                                                    <!-- Botón para ver detalle (modal) -->
                                                    <button class="btn btn-info btn-sm btn-detalle"
                                                        data-id="{{ $inscripcion->idInscripcion }}" title="Ver Detalle">
                                                        <i class="ri-eye-line"></i>
                                                    </button>

                                                    <!-- Botón para imprimir comprobante (no funcional) -->
                                                    <button class="btn btn-secondary btn-sm" title="Imprimir Comprobante">
                                                        <i class="ri-printer-line"></i>
                                                    </button>

                                                    <!-- Botón para anular venta -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                        <form
                                                            action="{{ route('admin.inscripciones.cancelar', $inscripcion->idInscripcion) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Anular Venta"
                                                                onclick="return confirm('¿Está seguro de anular esta venta?')">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @php
                                                        $esMembresia = $inscripcion->detalleInscripciones
                                                            ->where('tipoProducto', 'membresia')
                                                            ->isNotEmpty();
                                                        $esServicio = $inscripcion->detalleInscripciones
                                                            ->where('tipoProducto', 'servicio')
                                                            ->isNotEmpty();
                                                    @endphp

                                                    @if ($esMembresia && $inscripcion->estado == 'activa')
                                                        <!-- Botón para generar credencial -->
                                                        <a href="{{ route('admin.inscripciones.generarCredencial', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-primary btn-sm" title="Generar Credencial">
                                                            <i class="ri-qr-code-line"></i>
                                                        </a>

                                                        <!-- Botón para enviar QR por WhatsApp -->
                                                        <a href="{{ route('admin.inscripciones.enviarWhatsapp', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-success btn-sm" title="Enviar QR por WhatsApp">
                                                            <i class="ri-whatsapp-line"></i>
                                                        </a>
                                                    @endif

                                                    @if ($esServicio)
                                                        <!-- Botón para generar pase de entrada -->
                                                        <a href="{{ route('admin.inscripciones.generarPase', $inscripcion->idInscripcion) }}"
                                                            class="btn btn-warning btn-sm"
                                                            title="Generar Pase de Entrada">
                                                            <i class="ri-ticket-line"></i>
                                                        </a>
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

                <!-- Modal para ver detalle (puedes mantenerlo como está) -->
                <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog"
                    aria-labelledby="detalleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <!-- Contenido del modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="detalleModalLabel">Detalle de Venta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Aquí se cargará el detalle mediante AJAX -->
                                <div id="detalleContenido"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ... -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar DataTables para cada tabla
            $('#membresiasTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 10,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    // Resto de configuraciones de idioma
                },
            });

            $('#serviciosTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 10,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    // Resto de configuraciones de idioma
                },
            });

            // Evento para abrir el modal y cargar el detalle (ajusta la URL según el tipo de producto)
            $('.btn-detalle').on('click', function() {
                var idInscripcion = $(this).data('id');
                $('#detalleContenido').html('<p>Cargando...</p>');
                $('#detalleModal').modal('show');

                $.ajax({
                    url: '/admin/inscripciones/' + idInscripcion + '/detalle',
                    method: 'GET',
                    success: function(data) {
                        // Procesar y mostrar los detalles en el modal
                        var contenido = '<p><strong>Cliente:</strong> ' + data.cliente.nombre +
                            ' ' + data.cliente.primerApellido + '</p>';
                        contenido += '<p><strong>Fecha de Inscripción:</strong> ' + data
                            .fechaInscripcion + '</p>';
                        contenido += '<p><strong>Estado:</strong> ' + data.estado.charAt(0)
                            .toUpperCase() + data.estado.slice(1) + '</p>';
                        contenido += '<p><strong>Total Pago:</strong> ' + parseFloat(data
                            .totalPago).toFixed(2) + '</p>';
                        contenido += '<h5>Productos:</h5>';
                        contenido += '<ul>';
                        data.detalle_inscripciones.forEach(function(detalle) {
                            var producto = detalle.tipoProducto.charAt(0)
                            .toUpperCase() + detalle.tipoProducto.slice(1);
                            if (detalle.tipoProducto === 'membresia' && detalle
                                .membresia) {
                                producto += ' - ' + detalle.membresia.nombre;
                            } else if (detalle.tipoProducto === 'servicio' && detalle
                                .servicio) {
                                producto += ' - ' + detalle.servicio.nombre;
                            }
                            contenido += '<li>' + producto + ' - Precio: ' + parseFloat(
                                detalle.precio).toFixed(2) + '</li>';
                        });
                        contenido += '</ul>';

                        $('#detalleContenido').html(contenido);
                    },
                    error: function() {
                        $('#detalleContenido').html(
                            '<p>Ocurrió un error al cargar el detalle.</p>');
                    }
                });
            });
        });
    </script>
@endpush
