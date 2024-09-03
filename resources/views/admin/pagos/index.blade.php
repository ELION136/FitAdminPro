@extends('layouts.admin')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Asistencias</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>Inicio</a></li>
                    <i class="bi bi-three-dots-vertical"></i>
                    <li class="breadcrumb-item active" aria-current="page">Asistencias</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="mb-xl-0">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        14 Aug 2019
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate">
                        <li><a class="dropdown-item" href="javascript:void(0);">2015</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">2016</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">2017</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">2018</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Filtrar de Pagos</h4>
            </div>
            <div class="card-body">
                <!-- Formulario de Filtros -->
                <form action="{{ route('admin.pagos.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                                <input type="date" name="fechaInicio" id="fechaInicio" class="form-control"
                                    value="{{ $fechaInicio }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechaFin" class="form-label">Fecha Fin</label>
                                <input type="date" name="fechaFin" id="fechaFin" class="form-control"
                                    value="{{ $fechaFin }}">
                            </div>
                        </div>
                        
                            <div class="d-grid gap-2 d-md-flex mx-auto">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('admin.pagos.reporte', ['fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]) }}"
                                    class="btn btn-success">Generar PDF</a>
                           
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">Lista de Pagos</h4>
            </div>
            <div class="card-body">
                <!-- Tabla de Pagos -->
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="miTabla">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Plan</th>
                                <th>Monto (bs)</th>
                                <th>Fecha de Pago</th>
                                <th>Método de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagos as $pago)
                                <tr>
                                    <td>{{ $pago->membresia->cliente->nombre }} {{ $pago->membresia->cliente->primerApellido }}</td>
                                    <td>{{ $pago->membresia->planMembresia->nombrePlan }}</td>
                                    <td>{{ $pago->monto }}</td>
                                    <td>{{ $pago->fechaPago }}</td>
                                    <td>{{ $pago->metodoPago }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#miTabla').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 75, 100],
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

