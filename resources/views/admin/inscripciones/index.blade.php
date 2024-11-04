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
                    <form method="GET" action="{{ route('admin.inscripciones.index') }}" class="mb-2">
                        <div class="row g-1 align-items-end">
                            <div class="col-md-2">
                                <input type="date" name="fecha_inicio" class="form-control form-control-sm"
                                    placeholder="Fecha de Inicio" value="{{ request('fecha_inicio') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="fecha_fin" class="form-control form-control-sm"
                                    placeholder="Fecha de Fin" value="{{ request('fecha_fin') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="estado" class="form-control form-control-sm">
                                    <option value="">Todos los estados</option>
                                    <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa
                                    </option>
                                    <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }}>Vencida
                                    </option>
                                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>
                                        Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="ri-equalizer-fill me-2 align-bottom"></i> Aplicar
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary btn-sm w-100">
                                    <i class="ri-refresh-fill me-2 align-bottom"></i> Limpiar
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
                                                <td>
                                                    @if ($inscripcion->fechaFin && $inscripcion->fechaFin->isPast())
                                                        <span
                                                            class="text-danger">{{ $inscripcion->fechaFin->format('d/m/Y') }}</span>
                                                    @else
                                                        {{ $inscripcion->fechaFin ? $inscripcion->fechaFin->format('d/m/Y') : 'N/A' }}
                                                    @endif
                                                </td>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge text-{{ $inscripcion->estado == 'activa' ? 'info' : ($inscripcion->estado == 'vencida' ? 'danger' : 'warning') }} fw-bold">
                                                        {{ ucfirst($inscripcion->estado) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($inscripcion->estado == 'activa' && $inscripcion->cliente->qrCode)
                                                        <img src="{{ asset('storage/' . $inscripcion->cliente->qrCode) }}"
                                                            alt="Código QR de cliente" width="50">
                                                    @else
                                                        <p>Sin QR generado</p>
                                                    @endif
                                                </td>

                                                <td>{{ number_format($inscripcion->totalPago, 2) }}</td>
                                                @php
                                                    $hoy = \Carbon\Carbon::today();
                                                @endphp
                                                <td>
                                                    <!-- Botón para marcar como vencida -->
                                                    @if ($inscripcion->estado == 'activa' && $inscripcion->fechaFin && $inscripcion->fechaFin->lessThanOrEqualTo($hoy))
                                                        <button
                                                            onclick="marcarComoVencida({{ $inscripcion->idInscripcion }})"
                                                            class="btn btn-warning btn-sm" title="Marcar como Vencida">
                                                            <i class="ri-time-line"></i> Vencer
                                                        </button>
                                                    @endif

                                                    <!-- Botón para imprimir comprobante -->
                                                    @if ($inscripcion->estado == 'activa')
                                                        <a href="javascript:void(0);"
                                                            onclick="abrirVentanaPDF({{ $inscripcion->idInscripcion }}, '{{ $inscripcion->cliente->qrCode }}')"
                                                            class="btn btn-info btn-sm {{ $inscripcion->cliente->qrCode ? '' : 'disabled' }}"
                                                            title="Imprimir Comprobante">
                                                            <i class="ri-printer-line"></i>
                                                        </a>
                                                    @endif

                                                    <!-- Botón para anular venta -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                        <button onclick="anularVenta({{ $inscripcion->idInscripcion }})"
                                                            class="btn btn-danger btn-sm" title="Anular Venta">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    @endif


                                                    <!-- Botón para generar credencial y enviar por WhatsApp -->
                                                    @if ($inscripcion->estado == 'activa')
                                                        <a href="{{ route('admin.generar.qr-membresia', $inscripcion->cliente->idCliente) }}"
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
                                            <th>Fecha de Inscripción</th>
                                            <th>Estado</th>
                                            <th>Total Pagado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inscripcionesServicios as $inscripcion)
                                            <tr>
                                                <td>{{ $inscripcion->cliente->nombre }}
                                                    {{ $inscripcion->cliente->primerApellido }}</td>
                                                <td>{{ ucfirst($inscripcion->tipoProducto) }} -
                                                    {{ $inscripcion->producto }}</td>

                                                <td>{{ $inscripcion->fechaInscripcion ? $inscripcion->fechaInscripcion->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge text-{{ $inscripcion->estado == 'activa' ? 'info' : ($inscripcion->estado == 'vencida' ? 'danger' : 'warning') }} fw-bold">
                                                        {{ ucfirst($inscripcion->estado) }}
                                                    </span>
                                                </td>
                                                <td>{{ number_format($inscripcion->totalPago, 2) }}</td>
                                                <td>
                                                    <!-- Botón para abrir el modal de detalles de servicios -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                        <button
                                                            data-url="{{ route('admin.inscripciones.detalleServicios', ['id' => $inscripcion->idInscripcion]) }}"
                                                            onclick="verDetalleServicios(this)"
                                                            class="btn btn-info btn-sm" title="Ver Detalle de Servicios">
                                                            <i class="ri-eye-line"></i> Detalle
                                                        </button>
                                                    @endif

                                                    <!-- Botón para anular la inscripción -->
                                                    @if ($inscripcion->estado != 'cancelada')
                                                        <button onclick="anularVenta({{ $inscripcion->idInscripcion }})"
                                                            class="btn btn-danger btn-sm" title="Anular Venta">
                                                            <i class="ri-delete-bin-line"></i> Anular
                                                        </button>
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



    <!-- Modal de Detalle de Servicios -->
    <div class="modal fade" id="detalleServiciosModal" tabindex="-1" aria-labelledby="detalleServiciosLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleServiciosLabel">Detalle de Servicios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Precio</th>
                                <th>Código QR</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="detalleServiciosTableBody">
                            <!-- Los servicios se cargarán con JavaScript al abrir el modal -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Mostrar alertas de éxito y error con Swal si existen en la sesión
            const showAlert = (type, message) => {
                Swal.fire({
                    icon: type,
                    title: type === 'success' ? '¡Éxito!' : '¡Error!',
                    text: message
                });
            };

            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            // Inicializar DataTables para cada tabla
            const initDataTable = (selector) => {
                $(selector).DataTable({
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50, 100],
                    pageLength: 5,
                    language: {
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        emptyTable: "No hay datos disponibles en la tabla",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                        infoFiltered: "(filtrado de _MAX_ entradas totales)",
                        search: "Buscar:",
                        zeroRecords: "No se encontraron registros coincidentes",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });
            };

            initDataTable('#membresiasTable');
            initDataTable('#serviciosTable');

            // Función para abrir ventana con comprobante PDF
            const abrirVentanaPDF = (url, qrCode) => {
                if (!qrCode) {
                    showAlert('warning', 'El QR no está disponible. No se puede generar el comprobante.');
                    return;
                }
                window.open(url, '_blank', 'width=800,height=600');
            };

            window.abrirVentanaPDF = (idInscripcion, qrCode) => {
                abrirVentanaPDF(`{{ route('admin.inscripciones.comprobante2', ':id') }}`.replace(':id',
                    idInscripcion), qrCode);
            };

            window.abrirVentanaPDFServicio = (idDetalle, qrCode) => {
                abrirVentanaPDF(`{{ route('admin.inscripciones.comprobante_servicio', ':id') }}`.replace(':id',
                    idDetalle), qrCode);
            };

            // Función para anular venta con Swal
            window.anularVenta = (idInscripcion) => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, anular',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('admin.inscripciones.cancelar', ':id') }}`.replace(
                                ':id', idInscripcion),
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: (response) => {
                                showAlert('success', response.success);
                                location.reload();
                            },
                            error: () => showAlert('error',
                                'Hubo un problema al anular la venta.')
                        });
                    }
                });
            };

            // Función para ver detalle de servicios en modal
            window.verDetalleServicios = (button) => {
                const url = $(button).data('url');
                $.get(url, (response) => {
                    let tableBody = '';
                    response.detallesServicios.forEach(servicio => {
                        const qrImagePath = servicio.qrCode ?
                            `{{ asset('storage') }}/${servicio.qrCode}` : '';
                        tableBody += `
                <tr>
                    <td>${servicio.nombre}</td>
                    <td>${parseFloat(servicio.precio).toFixed(2)}</td>
                    <td>${servicio.qrCode ? `<img src="${qrImagePath}" width="50" />` : 'Sin QR'}</td>
                    <td>
                        <button onclick="generarQr(${servicio.idDetalle})" class="btn btn-primary btn-sm ${servicio.qrCode ? 'disabled' : ''}" title="Generar QR"><i class="ri-qr-code-line"></i></button>
                        <button onclick="generarComprobante(${servicio.idDetalle})" class="btn btn-warning btn-sm" title="Generar Comprobante"><i class="ri-printer-line"></i></button>
                    </td>
                </tr>`;
                    });
                    $('#detalleServiciosTableBody').html(tableBody);
                    $('#detalleServiciosModal').modal('show');
                }).fail(() => showAlert('error', 'No se pudo cargar el detalle de servicios.'));
            };

            // Función para generar QR
            window.generarQr = (idDetalle) => {
                $.post(`{{ route('admin.generar.qr-servicio', ':id') }}`.replace(':id', idDetalle), {
                        _token: '{{ csrf_token() }}'
                    })
                    .done((response) => {
                        showAlert('success', response.success);
                        location.reload();
                    })
                    .fail(() => showAlert('error', 'No se pudo generar el QR.'));
            };

            // Función para generar comprobante de servicio
            window.generarComprobante = (idDetalle) => {
                const url = `{{ route('admin.inscripciones.comprobante_servicio', ':id') }}`.replace(':id',
                    idDetalle);
                window.open(url, '_blank');
            };

            window.marcarComoVencida = (idInscripcion) => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción marcará la membresía como vencida.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, marcar como vencida',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('admin.inscripciones.marcarVencida', ':id') }}`
                                .replace(':id',
                                    idInscripcion),
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: (response) => {
                                showAlert('success', response.success);
                                location.reload();
                            },
                            error: (xhr) => {
                                const error = xhr.responseJSON.error ||
                                    'Hubo un problema al marcar la membresía como vencida.';
                                showAlert('error', error);
                            }
                        });
                    }
                });
            };
        });

    </script>
@endpush
