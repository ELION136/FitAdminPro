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
                    <a href="{{ route('admin.entrenadores.create') }}" class="btn btn-primary rounded-pill btn-wave"
                        data-bs-original-title="Añadir">
                        <i class="bi bi-clipboard-check"></i> Añadir un nuevo Empleado
                    </a>
                    <a href="{{ route('admin.entrenadores.eliminados') }}" class="btn btn-warning rounded-pill btn-wave"
                        data-bs-original-title="Añadir">
                        <i class="bi bi-archive"></i> Habilitar
                    </a>
                    <a href="{{ route('admin.entrenadores.pdf') }}" class="btn btn-danger rounded-pill btn-wave">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
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