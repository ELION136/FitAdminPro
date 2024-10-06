@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Membresias</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Planes</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="planes">


                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Planes de Membresia</h5>
                    <div class="d-flex gap-1 flex-wrap">
                        <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                        @if (auth()->user()->rol === 'Administrador')
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPlanModal"><i class="ri-add-line align-bottom me-1"></i>Crear nuevo
                        plan</button>
                        @endif
                    </div>
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
                                    <th>Estado</th>
                                    @if (auth()->user()->rol === 'Administrador')
                                    <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php $numero = 1; @endphp
                                @foreach ($planes as $plan)
                                    <tr>
                                        <td>{{ $numero++ }}</td>
                                        <td>{{ $plan->nombre }}</td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $plan->descripcion }}">
                                                {{ Str::limit($plan->descripcion, 50, '...') }}
                                            </span>
                                        </td>
                                        <td>{{ $plan->duracion }}</td>
                                        <td>{{ $plan->precio }}</td>
                                        <td>
                                            @if ($plan->eliminado== 1)
                                                <span class="text-success">Activo</span>
                                            @else
                                                <span class="text-danger">Inactivo</span>
                                            @endif
                                        </td>

                                        @if (auth()->user()->rol === 'Administrador')
                                        <td>
                                            
                                            <!-- Botón para abrir el modal de edición -->
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editPlanModal{{ $plan->idMembresia }}">
                                                <i class="ri-edit-2-line"></i>
                                            </button>

                                            <!-- Botón para eliminar con confirmación SweetAlert -->
                                            <form action="{{ route('admin.planes.destroy', $plan->idMembresia) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger show_confirm">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>

                                        </td>
                                        @endif
                                    </tr>

                                    <!--End::row-1 -->

                                    <!-- Modal para Editar Plan -->
                                    <div class="modal fade" id="editPlanModal{{ $plan->idMembresia }}" tabindex="-1"
                                        aria-labelledby="editPlanModalLabel{{ $plan->idMembresia}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.planes.update', $plan->idMembresia) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPlanModalLabel{{ $plan->idMembresia }}">
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
                                                            <label for="nombre" class="form-label">Nombre del
                                                                Plan</label>
                                                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                                                name="nombre" value="{{ $plan->nombre }}" required>
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
                            <label for="nombre" class="form-label">Nombre del Plan</label>
                            <input type="text" class="form-control @error('nombrePlan') is-invalid @enderror"
                                id="nombre" name="nombre" required>
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
