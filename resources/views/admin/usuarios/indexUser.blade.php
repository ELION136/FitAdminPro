@extends('layouts.admin')
@section('content')
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Data Tables</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Tables</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Tables</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                Crear Nuevo
            </button>
        </div>
        
        <div class="mb-xl-0">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Lista de usuarios</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="file-export" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $contador++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->rol }}</td>
                                    <td>
                                        <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <a href="javascript:;" class="btn btn-sm btn-danger" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @include('admin.usuarios.partials.create_modal')
            @include('admin.usuarios.partials.edit_modal')
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este usuario?</p>
                <p><strong>Nombre:</strong> <span id="userName"></span></p>
                <p><strong>Email:</strong> <span id="userEmail"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    var confirmDeleteButton = document.getElementById('confirmDelete');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-id');
        var userName = button.getAttribute('data-name');
        var userEmail = button.getAttribute('data-email');

        var modalUserName = deleteModal.querySelector('#userName');
        var modalUserEmail = deleteModal.querySelector('#userEmail');

        modalUserName.textContent = userName;
        modalUserEmail.textContent = userEmail;

        confirmDeleteButton.onclick = function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Realmente quieres eliminar al usuario ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.usuarios.destroy", "") }}/' + userId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        var modalInstance = bootstrap.Modal.getInstance(deleteModal);
                        modalInstance.hide();

                        if (data.success) {
                            var row = document.querySelector(`tr[data-id="${userId}"]`);
                            if (row) row.remove();
                            Swal.fire(
                                'Eliminado!',
                                data.message,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Hubo un error al eliminar el usuario',
                            'error'
                        );
                    });
                }
            });
        };
    });
</script>
@endpush