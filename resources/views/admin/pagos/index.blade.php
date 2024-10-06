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



    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Éxito',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif


    <!-- Estadísticas -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <i class="ri-equalizer-fill me-2 align-bottom"></i> 
                    Total Pagos: Bs{{ number_format($estadisticas['total_pagos'], 2) }}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <i class="ri-check-fill me-2 align-bottom"></i> 
                    Pagos Completados: {{ $estadisticas['pagos_completados'] }}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <i class="ri-time-fill me-2 align-bottom"></i> 
                    Pagos Pendientes: {{ $estadisticas['pagos_pendientes'] }}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <i class="ri-close-fill me-2 align-bottom"></i> 
                    Pagos Fallidos: {{ $estadisticas['pagos_fallidos'] }}
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
                    <form action="{{ route('admin.pagos.index') }}" method="GET" class="row g-3">
                        <!-- Filtro de estado -->
                        <div class="col-md-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="fallido" {{ request('estado') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                            </select>
                        </div>
                
                        <!-- Filtro por fecha desde -->
                        <div class="col-md-3">
                            <label for="fecha_desde" class="form-label">Fecha Desde</label>
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"
                                value="{{ request('fecha_desde') }}">
                        </div>
                
                        <!-- Filtro por fecha hasta -->
                        <div class="col-md-3">
                            <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"
                                value="{{ request('fecha_hasta') }}">
                        </div>
                
                        <!-- Filtro por monto mínimo -->
                        <div class="col-md-3">
                            <label for="monto_minimo" class="form-label">Monto Mínimo</label>
                            <input type="number" class="form-control" id="monto_minimo" name="monto_minimo"
                                value="{{ request('monto_minimo') }}" step="0.01">
                        </div>
                
                        <!-- Filtro por monto máximo -->
                        <div class="col-md-3">
                            <label for="monto_maximo" class="form-label">Monto Máximo</label>
                            <input type="number" class="form-control" id="monto_maximo" name="monto_maximo"
                                value="{{ request('monto_maximo') }}" step="0.01">
                        </div>
                
                        <!-- Botón para aplicar filtros -->
                        <div class="col-md-3">
                            <label class="form-label d-none d-md-block">&nbsp;</label> <!-- Espacio para alinear el botón -->
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-equalizer-fill me-2 align-bottom"></i> Aplicar Filtros
                            </button>
                        </div>
                
                        <!-- Botón para limpiar filtros -->
                        <div class="col-md-3">
                            <label class="form-label d-none d-md-block">&nbsp;</label> <!-- Espacio para alinear el botón -->
                            <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary w-100">
                                <i class="ri-refresh-fill me-2 align-bottom"></i> Limpiar Filtros
                            </a>
                        </div>
                    </form>
                </div>
                


                <!-- Tabla de Pagos -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservaTable" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Monto</th>
                                    <th>Fecha de Pago</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagos as $pago)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pago->reserva->cliente->nombre }}
                                            {{ $pago->reserva->cliente->primerApellido }}
                                        </td>
                                        <td>Bs{{ number_format($pago->monto, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pago->fechaPago)->format('d/m/Y H:i') }}</td>

                                        <td>
                                            <span
                                                class="badge bg-{{ $pago->estadoPago == 'completado' ? 'success' : ($pago->estadoPago == 'pendiente' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($pago->estadoPago) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.pagos.updateEstado', $pago->idPago) }}"
                                                method="POST" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <select name="estadoPago" class="form-select" onchange="this.form.submit()">
                                                    <option value="pendiente"
                                                        {{ $pago->estadoPago == 'pendiente' ? 'selected' : '' }}>Pendiente
                                                    </option>
                                                    <option value="completado"
                                                        {{ $pago->estadoPago == 'completado' ? 'selected' : '' }}>
                                                        Completado</option>
                                                    <option value="fallido"
                                                        {{ $pago->estadoPago == 'fallido' ? 'selected' : '' }}>Fallido
                                                    </option>
                                                </select>
                                            </form>

                            
                                            <form action="{{ route('admin.pagos.cancelar', $pago->idPago) }}"
                                                method="POST" class="d-inline-block" id="cancel-form-{{ $pago->idPago }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmCancel({{ $pago->idPago }})">Cancelar</button>
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
            $('#reservaTable').DataTable({
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

        function confirmCancel(pagoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + pagoId).submit();
                }
            });
        }
    </script>
@endpush
