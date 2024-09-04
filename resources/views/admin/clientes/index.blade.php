@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Lista de Clientes</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>listas</a></li><i class="bi bi-three-dots-vertical"></i>
                    <li>Clientes</li>
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
                        <h4 class="card-title mb-0">Clientes </h4><br>
                        <a href="javascript:void(0);" class="tx-inverse" data-bs-toggle="dropdown"><i
                                class="mdi mdi-dots-horizontal text-gray"></i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:void(0);">Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Another Action</a>
                            <a class="dropdown-item" href="javascript:void(0);">Something Else Here</a>
                        </div>
                    </div>
                    <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary rounded-pill btn-wave"
                        data-bs-original-title="Añadir">
                        <i class="bi bi-clipboard-check"></i> Añadir un nuevo Cliente
                    </a>
                    <a href="{{ route('admin.clientes.eliminados') }}" class="btn btn-warning rounded-pill btn-wave"
                        data-bs-original-title="Añadir">
                        <i class="bi bi-archive"></i> Habilitar
                    </a>
                    <a href="{{ route('admin.clientes.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100 datatable" id="miTabla">
                            <thead>
                                <tr>
                                    <th><span>#</span></th>
                                    <th><span>Foto de Perfil</span></th>
                                    <th><span>Nombre Completo</span></th>
                                    <th><span>Correo Electronico</span></th>
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
                                            @if ($cliente->usuario->image)
                                                <img src="{{ asset('storage/' . $cliente->usuario->image) }}"
                                                    alt="Foto de perfil" width="50" height="50">
                                            @else
                                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil"
                                                    width="50" height="50">
                                            @endif
                                        </td>
                                        <td>{{ $cliente->nombre }} {{ $cliente->primerApellido }}
                                            {{ $cliente->segundoApellido }}</td>

                                        <td>{{ $cliente->usuario->email }}</td>
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
                                                class="btn btn-sm btn-info btn-b" data-bs-toggle="tooltip" title=""
                                                data-bs-original-title="Editar">
                                                <i class="bi bi-pen-fill"></i>
                                            </a>
                                               
                                            @if (auth()->user()->rol === 'Administrador')

                                            <!--boton de habilitado y deshabilitado-->
                                            <!-- Botón para deshabilitar (Eliminación lógica) -->
                                            <form id="disable-form-{{ $cliente->idCliente }}"
                                                action="{{ route('admin.clientes.destroy', $cliente->idCliente) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    data-bs-toggle="tooltip" title="Deshabilitar"
                                                    onclick="confirmDisable({{ $cliente->idCliente }})">
                                                    <i class="bi bi-folder-x"></i>
                                                </button>
                                            </form>

                                            <!-- Botón para eliminar permanentemente -->
                                            <form id="force-delete-form-{{ $cliente->idCliente }}"
                                                action="{{ route('admin.clientes.forceDestroy', $cliente->idCliente) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip" title="Eliminar"
                                                    onclick="confirmForceDelete({{ $cliente->idCliente }})">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                            @endif

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
    
        function confirmForceDelete(idCliente) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El cliente será eliminado permanentemente y no podrás restaurarlo!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('force-delete-form-' + idCliente).submit();
                }
            });
        }
    </script>
    
@endsection
@include('partials.datatables-scripts')


