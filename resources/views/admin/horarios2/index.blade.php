@extends('layouts.admin')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Horarios</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>Pagina</a></li><i class="bi bi-three-dots-vertical"></i>
                    <li aria-current="page">horarios</li>
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablita" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Entrenador</th>
                                        <th>Servicio</th>
                                        <th>Día</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Capacidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($horarios as $horario)
                                        <tr>
                                            <td>{{ $horario->entrenador->nombre }}</td>
                                            <td>{{ $horario->servicio->nombre }}</td>
                                            <td>{{ $horario->dia }}</td>
                                            <td>{{ $horario->horaInicio }}</td>
                                            <td>{{ $horario->horaFin }}</td>
                                            <td>{{ $horario->capacidad }}</td>
                                            <td>
                                                <a href="{{ route('admin.horarios2.edit', $horario->idHorario) }}"
                                                    class="btn btn-warning">Editar</a>
                                                <form action="{{ route('admin.horarios2.destroy', $horario->idHorario) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tablita').DataTable({
                responsive: true, // Para asegurar que la tabla sea responsive
                lengthMenu: [2, 5, 10, 25, 50, 75,
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
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });



        @if (session('success'))
            Swal.fire({
                title: 'Éxito',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        document.querySelectorAll('.show_confirm').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                })
            });
        });
    </script>
@endpush
