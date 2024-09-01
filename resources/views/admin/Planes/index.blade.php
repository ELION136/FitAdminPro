@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Membresias</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>Pagina</a></li><i class="bi bi-three-dots-vertical"></i>
                    <li aria-current="page">Planes</li>
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
    <!-- Page Header Close -->

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Planes de Membresias </h4> <br>
                        <a href="javascript:void(0);" class="tx-inverse" data-bs-toggle="dropdown"><i
                                class="mdi mdi-dots-horizontal text-gray"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);">Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Another Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Something Else Here</a>
                        </div>
                    </div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPlanModal">Crear nuevo
                        plan</button>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablita" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Duración(dias)</th>
                                    <th>Precio(Bs)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $numero = 1; @endphp
                                @foreach ($planes as $plan)
                                    <tr>
                                        <td>{{ $numero++ }}</td>
                                        <td>{{ $plan->nombrePlan }}</td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $plan->descripcion }}">
                                                {{ Str::limit($plan->descripcion, 50, '...') }}
                                            </span>
                                        </td>
                                        <td>{{ $plan->duracion }}</td>
                                        <td>{{ $plan->precio }}</td>
                                        <td>
                                            <!-- Botón para abrir el modal de edición -->
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editPlanModal{{ $plan->idPlan }}">
                                                Editar
                                            </button>

                                            <!-- Botón para eliminar con confirmación SweetAlert -->
                                            <form action="{{ route('admin.planes.destroy', $plan->idPlan) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger show_confirm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!--End::row-1 -->

                                    <!-- Modal para Editar Plan -->
                                    <div class="modal fade" id="editPlanModal{{ $plan->idPlan }}" tabindex="-1"
                                        aria-labelledby="editPlanModalLabel{{ $plan->idPlan }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.planes.update', $plan->idPlan) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPlanModalLabel{{ $plan->idPlan }}">
                                                            Editar Plan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label for="nombrePlan" class="form-label">Nombre del
                                                                Plan</label>
                                                            <input type="text" class="form-control @error('nombrePlan') is-invalid @enderror" id="nombrePlan"
                                                                name="nombrePlan" value="{{ $plan->nombrePlan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="descripcion" class="form-label">Descripción</label>
                                                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion">{{ $plan->descripcion }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="duracion" class="form-label">Duración (días)</label>
                                                            <input type="number" class="form-control @error('duracion') is-invalid @enderror" id="duracion"
                                                                name="duracion" value="{{ $plan->duracion }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="precio" class="form-label">Precio(bs)</label>
                                                            <input type="number" step="0.01" class="form-control form-control @error('precio') is-invalid @enderror"
                                                                id="precio" name="precio"
                                                                value="{{ $plan->precio }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar
                                                            Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div><!-- COL END -->
    </div>
    <!-- Modal para Crear Plan -->
    <div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.planes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPlanModalLabel">Crear Nuevo Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="nombrePlan" class="form-label">Nombre del Plan</label>
                            <input type="text" class="form-control @error('nombrePlan') is-invalid @enderror"
                                id="nombrePlan" name="nombrePlan" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="duracion" class="form-label">Duración (días)</label>
                            <input type="number" class="form-control @error('duracion') is-invalid @enderror"
                                id="duracion" name="duracion" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio(bs)</label>
                            <input type="number" step="0.01"
                                class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Plan</button>
                    </div>
                </form>
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
