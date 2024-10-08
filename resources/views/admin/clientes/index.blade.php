@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Clientes</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Nomina</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
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

                    <h5 class="card-title mb-0">Clientes </h5><br>


                    <a href="{{ route('admin.clientes.create') }}"
                        class="btn btn-outline-info waves-effect waves-light material-shadow-none"
                        data-bs-original-title="Añadir">
                        <i class="las la-plus"></i> Añadir un nuevo Cliente
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle datatable" id="miTabla">
                            <thead>
                                <tr>
                                    <th><span>#</span></th>
                                    <th><span>Imagen</span></th>
                                    <th><span>Nombre Completo</span></th>
                                    <th><span>Telefono de emergencia</span></th>
                                    <th><span>Género</span></th>
                                    <th><span>Edad</span></th>
                                    <th><span>Estado</span></th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($cliente->image)
                                                <img src="{{ asset('storage/' . $cliente->image) }}" alt="Foto de perfil"
                                                    width="50" height="50">
                                            @else
                                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil"
                                                    width="50" height="50">
                                            @endif
                                        </td>
                                        <td>{{ $cliente->nombre }} {{ $cliente->primerApellido }}
                                            {{ $cliente->segundoApellido }}</td>
                                            <td>{{ $cliente->telefonoEmergencia}}</td>

                                        <td>{{ $cliente->genero }}</td> <!-- Mostrar el género -->
                                        <td>{{ \Carbon\Carbon::parse($cliente->fechaNacimiento)->age }} años</td>
                                        <!-- Mostrar la edad -->
                                        <td>
                                            @if ($cliente->eliminado)
                                                <span class="text-success">Activo</span>
                                            @else
                                                <span class="text-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>

                                            <a href="{{ route('admin.clientes.edit', $cliente->idCliente) }}"
                                                class="btn btn-sm btn-warning btn-b" data-bs-toggle="tooltip" title=""
                                                data-bs-original-title="Editar">
                                                <i class="ri-sip-fill"></i>
                                            </a>

                                            <form id="disable-form-{{ $cliente->idCliente }}"
                                                action="{{ route('admin.clientes.destroy', $cliente->idCliente) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip" title="Deshabilitar"
                                                    onclick="confirmDisable({{ $cliente->idCliente }})">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modalCliente{{ $cliente->idCliente }}">
                                                <i class="las la-eye"></i>
                                            </button>


                                            <!-- Modal -->
                                            <div class="modal fade" id="modalCliente{{ $cliente->idCliente }}"
                                                tabindex="-1"
                                                aria-labelledby="modalVerEntrenadorLabel{{ $cliente->idCliente }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <!-- Modal Header -->
                                                        <div class="modal-header text-white">
                                                            <h5 class="modal-title"
                                                                id="modalVerEntrenadorLabel{{ $cliente->idCliente }}">
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
                                                                            <img src="{{ $cliente->image ? asset('storage/' . $cliente->image) : asset('images/default-profile.png') }}"
                                                                                alt="Imagen de perfil"
                                                                                class="img-fluid rounded-circle mb-3"
                                                                                width="150" height="150">
                                                                            <h5 class="card-title">
                                                                                {{ $cliente->nombre }} </h5>
                                                                            <p class="text-muted">
                                                                                {{ $cliente->nombre }}
                                                                                {{ $cliente->primerApellido }}
                                                                                {{ $cliente->segundoApellido }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Columna 2: Información -->
                                                                <div class="col-md-6">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <!-- Correo -->

                                                                            <!-- Género -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-venus-mars text-primary"></i>
                                                                                <strong>Género:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ $cliente->genero }}</p>
                                                                            </div>
                                                                            <!-- Edad -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-birthday-cake text-primary"></i>
                                                                                <strong>Edad:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($cliente->fechaNacimiento)->age }}
                                                                                    años</p>
                                                                            </div>
                                                                            <!-- Estado -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-user-check text-primary"></i>
                                                                                <strong>Estado:</strong>
                                                                                <p
                                                                                    class="{{ $cliente->eliminado ? 'text-success' : 'text-danger' }}">
                                                                                    {{ $cliente->eliminado ? 'Activo' : 'Inactivo' }}
                                                                                </p>
                                                                            </div>
                                                                            <!-- Fecha de Registro -->
                                                                            <div class="mb-3">
                                                                                <i
                                                                                    class="fas fa-calendar-alt text-primary"></i>
                                                                                <strong>Fecha de Registro:</strong>
                                                                                <p class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($cliente->fechaCreacion)->format('d/m/Y H:i') }}
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
        function confirmDisable(idCliente) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El cliente será deshabilitado y podrás restaurarlo más tarde!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deshabilitarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('disable-form-' + idCliente).submit();
                }
            });
        }
    </script>
    @endsection
    @include('partials.datatables-scripts')
