@extends('layouts.admin')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Asistencias</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>Incio</a></li><i class="bi bi-three-dots-vertical"></i>
                    <li aria-current="page">Asistencias</li>
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
        <div class="container">




            <!-- Mensajes de éxito y error usando SweetAlert -->
            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ session('error') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>
            @endif

            <!-- Tabla de asistencias -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Asistencias</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100" id="miTabla">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Hora Entrada</th>
                                <th>Hora Salida</th>
                                <th>Acciones</th> <!-- Nueva columna para los botones de acción -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asistencias as $asistencia)
                                <tr>
                                    <td>{{ $asistencia->cliente->nombre }}</td>
                                    <td>{{ $asistencia->fecha }}</td>
                                    <td>{{ $asistencia->horaEntrada }}</td>
                                    <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
                                    <td>
                                        <!-- Botón de Editar -->
                                        <a href="{{ route('admin.asistencias.edit', $asistencia->idAsistencia) }}"
                                            class="btn btn-sm btn-warning">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $asistencias->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#miTabla').DataTable({
                dom: 'Bfrtip',
                responsive: true, // Para asegurar que la tabla sea responsive
                lengthMenu: [5,10, 25, 50, 75, 100], // Opción para seleccionar la cantidad de registros por página
                pageLength: 5,
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-primary'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
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
