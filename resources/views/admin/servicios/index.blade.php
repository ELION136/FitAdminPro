@extends('layouts.admin')

@section('content')
    <!-- Page Header -->

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Servicios</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Servicios</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="apiKeyList">

                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Servicios</h5>
                    <div class="d-flex gap-1 flex-wrap">
                        <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                class="ri-delete-bin-2-line"></i></button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createServicioModal"><i class="ri-add-line align-bottom me-1"></i> Añadir
                            nuevo Servicio</button>
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
                                    <th>Precio (Bs)</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
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
                                                {{ Str::limit($servicio->descripcion, 25, '...') }}
                                            </span>
                                        </td>
                                        <td>{{ $servicio->duracion }}</td>
                                        <td>{{ $servicio->precio }}</td>
                                        <td>{{ $servicio->fechaInicio->format('d/m/Y') }}</td>
                                        <td>{{ $servicio->fechaFin->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($servicio->eliminado == 1)
                                                <span class="text-success">Activo</span>
                                            @else
                                                <span class="text-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Botón para abrir el modal de edición -->
                                            <button type="button"
                                                class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editServicioModal{{ $servicio->idServicio }}"
                                                title="Editar">
                                                <i class="las la-pen"></i>
                                            </button>
                                            <!-- Botón para eliminar con confirmación SweetAlert -->
                                            <form action="{{ route('admin.servicios.destroy', $servicio->idServicio) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Eliminar"
                                                    class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none show_confirm">
                                                    <i class=" las la-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal para Editar Servicio -->
                                    <div class="modal fade" id="editServicioModal{{ $servicio->idServicio }}"
                                        tabindex="-1" aria-labelledby="editServicioModalLabel{{ $servicio->idServicio }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.servicios.update', $servicio->idServicio) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editServicioModalLabel{{ $servicio->idServicio }}">Editar
                                                            Servicio</h5>
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
                                                                Servicio</label>
                                                            <input type="text"
                                                                class="form-control @error('nombre') is-invalid @enderror"
                                                                id="nombre" name="nombre"
                                                                value="{{ $servicio->nombre }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="descripcion" class="form-label">Descripción</label>
                                                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" required>{{ $servicio->descripcion }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="duracion" class="form-label">Duración
                                                                (minutos)
                                                            </label>
                                                            <input type="number"
                                                                class="form-control @error('duracion') is-invalid @enderror"
                                                                id="duracion" name="duracion"
                                                                value="{{ $servicio->duracion }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="precio" class="form-label">Precio (Bs)</label>
                                                            <input type="number"
                                                                class="form-control @error('precio') is-invalid @enderror"
                                                                id="precio" name="precio"
                                                                value="{{ $servicio->precio }}" required>
                                                        </div>
                                                        <!-- Añadir campos para fechaInicio y fechaFin -->
                                                        <div class="mb-3">
                                                            <label for="fechaInicio" class="form-label">Fecha de
                                                                Inicio</label>
                                                            <input type="date"
                                                                class="form-control @error('fechaInicio') is-invalid @enderror"
                                                                id="fechaInicio" name="fechaInicio"
                                                                value="{{ is_string($servicio->fechaInicio) ? $servicio->fechaInicio : $servicio->fechaInicio->format('Y-m-d') }}"
                                                                required min="{{ date('Y-m-d') }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="fechaFin" class="form-label">Fecha de Fin</label>
                                                            <input type="date"
                                                                class="form-control @error('fechaFin') is-invalid @enderror"
                                                                id="fechaFin" name="fechaFin"
                                                                value="{{ is_string($servicio->fechaFin) ? $servicio->fechaFin : $servicio->fechaFin->format('Y-m-d') }}"
                                                                required min="{{ date('Y-m-d') }}" required>
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
                        <!-- Campo para Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Servicio</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre"  required>
                        </div>
                        <!-- Campo para Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                required></textarea>
                        </div>
                        <!-- Campo para Duración -->
                        <div class="mb-3">
                            <label for="duracion" class="form-label">Duración (minutos)</label>
                            <input type="number" class="form-control @error('duracion') is-invalid @enderror"
                                id="duracion" name="duracion" required>
                        </div>
                        <!-- Campo para Precio -->
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (Bs)</label>
                            <input type="number" class="form-control @error('precio') is-invalid @enderror"
                                id="precio" name="precio" required>
                        </div>
                        <!-- Campo para Fecha Inicio -->
                        <div class="mb-3">
                            <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control @error('fechaInicio') is-invalid @enderror"
                                id="fechaInicio" name="fechaInicio" required min="{{ date('Y-m-d') }}" required>
                        </div>
                        <!-- Campo para Fecha Fin -->
                        <div class="mb-3">
                            <label for="fechaFin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control @error('fechaFin') is-invalid @enderror"
                                id="fechaFin" name="fechaFin" required min="{{ date('Y-m-d') }}" required>
                        </div>
                        <!-- Botones -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Crear Servicio</button>
                        </div>
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
