@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Membresías</h4>
                <button class="btn btn-primary" onclick="createMembresia()">Añadir Membresía</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Duración (días)</th>
                                <th>Precio (BOB)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($membresias as $membresia)
                                <tr>
                                    <td>{{ $membresia->nombre }}</td>
                                    <td>{{ $membresia->descripcion }}</td>
                                    <td>{{ $membresia->duracionDias }}</td>
                                    <td>{{ number_format($membresia->precio, 2, '.', ',') }} BOB</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="editMembresia({{ $membresia }})">Editar</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteMembresia({{ $membresia->idMembresia }})">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear/Editar Membresía -->
<div class="modal fade" id="modalMembresia" tabindex="-1" aria-labelledby="modalMembresiaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formMembresia" method="POST">
                @csrf
                <input type="hidden" id="membresiaId" name="membresiaId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMembresiaLabel">Añadir/Editar Membresía</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="duracionDias">Duración (días)</label>
                        <input type="number" class="form-control" id="duracionDias" name="duracionDias" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    let form = document.getElementById('formMembresia');

    // Función para abrir el modal de creación de una nueva membresía
    function createMembresia() {
        form.reset(); // Restablecer los campos del formulario
        form.membresiaId.value = ''; // Limpiar el campo de ID
        document.getElementById('modalMembresiaLabel').textContent = 'Añadir Membresía'; // Cambiar el título del modal
        new bootstrap.Modal(document.getElementById('modalMembresia')).show(); // Mostrar el modal
    }

    // Función para abrir el modal de edición de una membresía existente
    function editMembresia(membresia) {
        form.reset(); // Restablecer los campos del formulario
        form.membresiaId.value = membresia.idMembresia; // Asignar el ID de la membresía
        form.nombre.value = membresia.nombre;
        form.descripcion.value = membresia.descripcion;
        form.duracionDias.value = membresia.duracionDias;
        form.precio.value = membresia.precio;
        document.getElementById('modalMembresiaLabel').textContent = 'Editar Membresía'; // Cambiar el título del modal
        new bootstrap.Modal(document.getElementById('modalMembresia')).show(); // Mostrar el modal
    }

    // Crear o Editar Membresía
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let membresiaId = form.membresiaId.value;
        let url = membresiaId ? `{{ route('admin.membresias.update', '') }}/${membresiaId}` : `{{ route('admin.membresias.store') }}`;
        let formData = new FormData(form);

        if (membresiaId) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST', // Usamos POST para enviar, aunque sea un PUT o DELETE con _method
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                let errors = Object.values(data.errors).map(err => err.join('<br>')).join('<br>');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errors,
                    confirmButtonText: 'Aceptar'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Membresía guardada correctamente',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload();
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error en el servidor',
                confirmButtonText: 'Aceptar'
            });
            console.error('Error:', error);
        });
    });

    // Función para eliminar una membresía
    function deleteMembresia(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ route('admin.membresias.destroy', '') }}/${id}`, {
                    method: 'POST', // Usamos POST para enviar el _method: DELETE
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'La membresía ha sido eliminada correctamente',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ha ocurrido un error en el servidor',
                        confirmButtonText: 'Aceptar'
                    });
                    console.error('Error:', error);
                });
            }
        });
    }
</script>
@endpush
