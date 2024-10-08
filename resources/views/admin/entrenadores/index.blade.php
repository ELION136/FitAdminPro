@extends('layouts.app')

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
                    <i class="las la-plus"></i> Añadir un nuevo Entrenador
                </a> 
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100 datatable" id="miTabla">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Género</th>
                                <th>Edad</th>
                                <th>Telefono</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entrenadores as $entrenador)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($entrenador->image)
                                            <img src="{{ asset('storage/' . $entrenador->image) }}" alt="Foto de perfil" width="50" height="50">
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil" width="50" height="50">
                                        @endif
                                    </td>
                                    <td>{{ $entrenador->nombre }} {{ $entrenador->primerApellido }} {{ $entrenador->segundoApellido }}</td>
                                    <td>{{ $entrenador->genero }}</td>
                                    <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->age }} años</td>
                                    <td>{{ $entrenador->telefono}}</td>
                                    <td>
                                        @if ($entrenador->eliminado)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.entrenadores.edit', $entrenador->idEntrenador) }}" class="btn btn-sm btn-info btn-b" data-bs-toggle="tooltip" title="Editar">
                                            <i class="ri-sip-fill"></i>
                                        </a>

                                        <!-- Botón para deshabilitar (Eliminación lógica) -->
                                        <form id="disable-form-{{ $entrenador->idEntrenador }}" action="{{ route('admin.entrenadores.destroy', $entrenador->idEntrenador) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Deshabilitar"
                                                onclick="confirmDisable({{ $entrenador->idEntrenador }})">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </form>
                                        <!-- Botón para ver detalles en un modal -->
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalVerEntrenador{{ $entrenador->idEntrenador }}">
                                            <i class="las la-eye"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalVerEntrenador{{ $entrenador->idEntrenador }}" tabindex="-1" aria-labelledby="modalVerEntrenadorLabel{{ $entrenador->idEntrenador }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header text-white">
                                                        <h5 class="modal-title" id="modalVerEntrenadorLabel{{ $entrenador->idEntrenador }}">
                                                            <i class="fas fa-user"></i> Detalles del Entrenador
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <!-- Modal Body -->
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <!-- Columna 1: Imagen -->
                                                            <div class="col-md-6 text-center">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <img src="{{ $entrenador->image ? asset('storage/' . $entrenador->image) : asset('images/default-profile.png') }}" alt="Imagen de perfil" class="img-fluid rounded-circle mb-3" width="150" height="150">
                                                                        <h5 class="card-title">{{ $entrenador->nombre }} {{ $entrenador->primerApellido }} {{ $entrenador->segundoApellido }}</h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Columna 2: Información -->
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <!-- Género -->
                                                                        <div class="mb-3">
                                                                            <i class="fas fa-venus-mars text-primary"></i>
                                                                            <strong>Género:</strong>
                                                                            <p class="text-muted">{{ $entrenador->genero }}</p>
                                                                        </div>
                                                                        <!-- Edad -->
                                                                        <div class="mb-3">
                                                                            <i class="fas fa-birthday-cake text-primary"></i>
                                                                            <strong>Edad:</strong>
                                                                            <p class="text-muted">{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->age }} años</p>
                                                                        </div>
                                                                        <!-- Estado -->
                                                                        <div class="mb-3">
                                                                            <i class="fas fa-user-check text-primary"></i>
                                                                            <strong>Estado:</strong>
                                                                            <p class="{{ $entrenador->eliminado ? 'text-success' : 'text-danger' }}">{{ $entrenador->eliminado ? 'Activo' : 'Inactivo' }}</p>
                                                                        </div>
                                                                        <!-- Fecha de Registro -->
                                                                        <div class="mb-3">
                                                                            <i class="fas fa-calendar-alt text-primary"></i>
                                                                            <strong>Fecha de Registro:</strong>
                                                                            <p class="text-muted">{{ \Carbon\Carbon::parse($entrenador->fechaCreacion)->format('d/m/Y H:i') }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

</script>
@endsection

@include('partials.datatables-scripts')
@push('scripts')
@endpush
