@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Servicios</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                   
                    <li class="breadcrumb-item active" aria-current="page">Servicios</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createServicioModal">Crear nuevo servicio</button>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Servicios Disponibles</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="serviciosTable" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Duración (minutos)</th>
                                    <th>Tipo</th>
                                    <th>Categoría</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $numero = 1; @endphp
                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td>{{ $numero++ }}</td>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $servicio->descripcion }}">
                                                {{ Str::limit($servicio->descripcion, 50, '...') }}
                                            </span>
                                        </td>
                                        <td>{{ $servicio->duracion }}</td>
                                        <td>{{ $servicio->tipoServicio }}</td>
                                        <td>{{ $servicio->categoria }}</td>
                                        <td>
                                            <!-- Botón para abrir el modal de edición -->
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editServicioModal{{ $servicio->idServicio }}">
                                                Editar
                                            </button>

                                            <!-- Botón para eliminar con confirmación SweetAlert -->
                                            <form action="{{ route('admin.servicios.destroy', $servicio->idServicio) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger show_confirm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal para Editar Servicio -->
                                    <div class="modal fade" id="editServicioModal{{ $servicio->idServicio }}" tabindex="-1"
                                        aria-labelledby="editServicioModalLabel{{ $servicio->idServicio }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.servicios.update', $servicio->idServicio) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editServicioModalLabel{{ $servicio->idServicio }}">
                                                            Editar Servicio</h5>
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
                                                            <label for="nombre" class="form-label">Nombre del Servicio</label>
                                                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                                                name="nombre" value="{{ $servicio->nombre }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="descripcion" class="form-label">Descripción</label>
                                                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                                                name="descripcion" required>{{ $servicio->descripcion }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="duracion" class="form-label">Duración (minutos)</label>
                                                            <input type="number" class="form-control @error('duracion') is-invalid @enderror" id="duracion"
                                                                name="duracion" value="{{ $servicio->duracion }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tipoServicio" class="form-label">Tipo de Servicio</label>
                                                            <select class="form-select @error('tipoServicio') is-invalid @enderror" id="tipoServicio" name="tipoServicio" required>
                                                                <option value="Individual" {{ $servicio->tipoServicio == 'Individual' ? 'selected' : '' }}>Individual</option>
                                                                <option value="Grupal" {{ $servicio->tipoServicio == 'Grupal' ? 'selected' : '' }}>Grupal</option>
                                                                <option value="Online" {{ $servicio->tipoServicio == 'Online' ? 'selected' : '' }}>Online</option>
                                                                <option value="Presencial" {{ $servicio->tipoServicio == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="categoria" class="form-label">Categoría</label>
                                                            <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                                                                <option value="Entrenamiento" {{ $servicio->categoria == 'Entrenamiento' ? 'selected' : '' }}>Entrenamiento</option>
                                                                <option value="Nutrición" {{ $servicio->categoria == 'Nutrición' ? 'selected' : '' }}>Nutrición</option>
                                                                <option value="Rehabilitación" {{ $servicio->categoria == 'Rehabilitación' ? 'selected' : '' }}>Rehabilitación</option>
                                                                <option value="Otro" {{ $servicio->categoria == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
        </div>
    </div>
    <!-- End::row-1 -->

    <!-- Modal para Crear Servicio -->
    <div class="modal fade" id="createServicioModal" tabindex="-1" aria-labelledby="createServicioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.servicios.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createServicioModalLabel">Crear Nuevo Servicio</h5>
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
                            <label for="nombre" class="form-label">Nombre del Servicio</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="duracion" class="form-label">Duración (minutos)</label>
                            <input type="number" class="form-control @error('duracion') is-invalid @enderror"
                                id="duracion" name="duracion" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipoServicio" class="form-label">Tipo de Servicio</label>
                            <select class="form-select @error('tipoServicio') is-invalid @enderror" id="tipoServicio" name="tipoServicio" required>
                                <option value="Individual">Individual</option>
                                <option value="Grupal">Grupal</option>
                                <option value="Online">Online</option>
                                <option value="Presencial">Presencial</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                                <option value="Entrenamiento">Entrenamiento</option>
                                <option value="Nutrición">Nutrición</option>
                                <option value="Rehabilitación">Rehabilitación</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
