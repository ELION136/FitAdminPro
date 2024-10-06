@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Entrenadores</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Nomina</a></li>
                        <li class="breadcrumb-item active">Entrenadores</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lista de Entrenadores</h5><br>
                    <a href="{{ route('admin.entrenadores.create') }}" class="btn btn-outline-primary waves-effect waves-light material-shadow-none"
                        data-bs-original-title="Añadir">
                        <i class="las la-plus"></i> Añadir un nuevo Empleado
                    </a> 
                
                    <a href="{{ route('admin.entrenadores.eliminados') }}" class="btn btn-outline-warning waves-effect waves-light material-shadow-none"
                        data-bs-original-title="Añadir">
                        <i class="las la-archive"></i> Habilitar
                    </a>
                    <a href="{{ route('admin.entrenadores.pdf') }}" class="btn btn-outline-danger waves-effect waves-light material-shadow-none">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                    <a href="{{ route('admin.entrenadores.export.excel') }}" class="btn btn-outline-success waves-effect waves-light material-shadow-none">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                    
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100 datatable" id="miTabla">
                            <thead>
                                <tr>
                                    <th><span>#</span></th>
                                    <th><span>Imagen</span></th>
                                    <th><span>Nombre</span></th>
                                    <th><span>Correo Electronico</span></th>
                                    <th><span>Género</span></th>
                                    <th><span>Edad</span></th>
                                    <th><span>Estado</span></th>

                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entrenadores as $entrenador)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>

                                            @if ($entrenador->usuario->image)
                                                <img src="{{ asset('storage/' . $entrenador->usuario->image) }}"
                                                    alt="Foto de perfil" width="50" height="50">
                                            @else
                                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil"
                                                    width="50" height="50">
                                            @endif
                                        </td>
                                        <td>{{ $entrenador->nombre }} {{ $entrenador->primerApellido }}
                                            {{ $entrenador->segundoApellido }}</td>

                                        <td>{{ $entrenador->usuario->email }}</td>
                                        <td>{{ $entrenador->genero }}</td> <!-- Mostrar el género -->
                                        <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->age }} años</td>
                                        <td>
                                            @if ($entrenador->eliminado)
                                                <span class="text-success">Activo</span>
                                            @else
                                                <span class="text-danger">Inactivo</span>
                                            @endif
                                        </td>

                                        <td>

                                            <a href="{{ route('admin.entrenadores.edit', $entrenador->idEntrenador) }}"
                                                class="btn btn-sm btn-info btn-b" data-bs-toggle="tooltip" title=""
                                                data-bs-original-title="Editar">
                                                <i class="ri-sip-fill"></i>
                                            </a>


                                            <!--boton de habilitado y deshabilitado-->
                                            <!-- Botón para deshabilitar (Eliminación lógica) -->
                                            <form id="disable-form-{{ $entrenador->idEntrenador }}"
                                                action="{{ route('admin.entrenadores.destroy', $entrenador->idEntrenador) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    data-bs-toggle="tooltip" title="Deshabilitar"
                                                    onclick="confirmDisable({{ $entrenador->idEntrenador }})">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>

                                            <!-- Botón para eliminar permanentemente -->
                                            <form id="delete-form-{{ $entrenador->idEntrenador }}"
                                                action="{{ route('admin.entrenadores.forceDestroy', $entrenador->idEntrenador) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeletion({{ $entrenador->idEntrenador }})"
                                                    data-bs-toggle="tooltip" title="Eliminar">
                                                    <i class=" ri-close-circle-fill"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modalVerEntrenador{{ $entrenador->idEntrenador }}">
                                                <i class="las la-eye"></i>
                                            </button>


                                            <!-- Modal -->
                                            <div class="modal fade" id="modalVerEntrenador{{ $entrenador->idEntrenador }}"
                                                tabindex="-1"
                                                aria-labelledby="modalVerEntrenadorLabel{{ $entrenador->idEntrenador }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header text-white">
                                                            <h5 class="modal-title"
                                                                id="modalVerEntrenadorLabel{{ $entrenador->idEntrenador }}">
                                                                <i class="fas fa-user"></i> Detalles del Entrenador
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <!-- Modal Body -->
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <!-- Columna 1: Imagen -->
                                                                <div class="col-md-6 text-center">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <img src="{{ $entrenador->usuario->image ? asset('storage/' . $entrenador->usuario->image) : asset('images/default-profile.png') }}"
                                                                                alt="Imagen de perfil"
                                                                                class="img-fluid rounded-circle mb-3"
                                                                                width="150" height="150">
                                                                            <h5 class="card-title">
                                                                                {{$entrenador->usuario->nombreUsuario}} </h5> 
                                                                                <p class="text-muted">
                                                                                {{ $entrenador->nombre }}
                                                                                {{ $entrenador->primerApellido }}
                                                                                {{ $entrenador->segundoApellido }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Columna 2: Información -->
                                                                <div class="col-md-6">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <!-- Correo -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-envelope text-primary"></i>
                                                                                <strong>Correo Electrónico:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ $entrenador->usuario->email }}</p>
                                                                            </div>
                                                                            <!-- Género -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-venus-mars text-primary"></i>
                                                                                <strong>Género:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ $entrenador->genero }}</p>
                                                                            </div>
                                                                            <!-- Edad -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-birthday-cake text-primary"></i>
                                                                                <strong>Edad:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->age }}
                                                                                    años</p>
                                                                            </div>
                                                                            <!-- Estado -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-user-check text-primary"></i>
                                                                                <strong>Estado:</strong>
                                                                                <p
                                                                                    class="{{ $entrenador->eliminado ? 'text-success' : 'text-danger' }}">
                                                                                    {{ $entrenador->eliminado ? 'Activo' : 'Inactivo' }}
                                                                                </p>
                                                                            </div>
                                                                            <!-- Fecha de Registro -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-calendar-alt text-primary"></i>
                                                                                <strong>Fecha de Registro:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($entrenador->created_at)->format('d/m/Y H:i') }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Modal Footer -->
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
    </div>
    <!--End::row-1 -->

    <script>
        function confirmDisable(idEntrenador) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El entrenador será deshabilitado y podrás restaurarlo más tarde!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deshabilitarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('disable-form-' + idEntrenador).submit();
                }
            });
        }

        function confirmDeletion(idEntrenador) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El entrenador será eliminado permanentemente y no podrás restaurarlo!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + idEntrenador).submit();
                }
            });
        }
    </script>
@endsection

@include('partials.datatables-scripts')
@push('scripts')
@endpush
