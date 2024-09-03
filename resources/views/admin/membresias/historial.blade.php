@extends('layouts.admin')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Historial de Inscripciones</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>listas</a></li><i class="bi bi-three-dots-vertical"></i>
                    <li aria-current="page">Inscripciones</li>
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

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Inscripciones </h4> <br>
                        <a href="javascript:void(0);" class="tx-inverse" data-bs-toggle="dropdown"><i
                                class="mdi mdi-dots-horizontal text-gray"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);">Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Another Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Something Else Here</a>
                        </div>
                    </div>
                </div>



                <div class="card-body">
                    <div>
                        <form method="GET" action="{{ route('admin.membresias.historial') }}" class="d-inline">
                            <input type="date" name="fecha_inicio" required>
                            <input type="date" name="fecha_fin" required>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form>
                        <a href="{{ route('admin.membresias.generarPDF') }}" class="btn btn-secondary">Generar Reporte PDF</a>
                    </div>
                    <table class="table table-bordered text-nowrap w-100" id="miTabla">
                        <thead>
                            <tr>
                                <th>Miembro</th>
                                <th>Plan</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($membresias as $membresia)
                                <tr>
                                    <td>{{ $membresia->cliente->nombre }} {{ $membresia->cliente->primerApellido }}
                                        {{ $membresia->cliente->segundoApellido }}</td>
                                    <td>{{ $membresia->planMembresia->nombrePlan }}</td>
                                    <td>{{ $membresia->fechaInicio }}</td>
                                    <td>{{ $membresia->fechaFin }}</td>
                                    <td>{{ $membresia->estado }}</td>
                                    <td>
                                        <a href="{{ route('admin.membresias.generarCredencial', ['id' => $membresia->idMembresia]) }}" class="btn btn-secondary">Generar Credencial</a>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div><!-- COL END -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#miTabla').DataTable({
                responsive: true, // Para asegurar que la tabla sea responsive
                lengthMenu: [5, 10, 25, 50, 75,
                100], // Opción para seleccionar la cantidad de registros por página
                pageLength: 5,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    decimal: "",
                    emptyTable: "No hay datos disponibles en la tabla",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                    infoFiltered: "(filtrado de _MAX_ entradas totales)",
                    infoPostFix: "",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ entradas",
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
