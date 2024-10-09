@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Membresías</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMembresia">Añadir Membresía</button>
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
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($membresias as $membresia)
                                <tr>
                                    <td>{{ $membresia->nombre }}</td>
                                    <td>{{ $membresia->descripcion }}</td>
                                    <td>{{ $membresia->duracionDias }}</td>
                                    <td>{{ $membresia->precio }}</td>
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
                        <input type="number" class="form-control" id="precio" name="precio" required>
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

    // Crear o Editar Membresía
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let membresiaId = form.membresiaId.value;
        let url = membresiaId ? `{{ route('admin.membresias.update', '') }}/${membresiaId}` : `{{ route('admin.membresias.store') }}`;
        let method = membresiaId ? 'POST' : 'POST';
        let formData = new FormData(form);
        
        if(membresiaId) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                alert(JSON.stringify(data.errors));
            } else {
                location.reload();
            }
        });
    });

    function editMembresia(membresia) {
        form.reset();
        form.membresiaId.value = membresia.idMembresia;
        form.nombre.value = membresia.nombre;
        form.descripcion.value = membresia.descripcion;
        form.duracionDias.value = membresia.duracionDias;
        form.precio.value = membresia.precio;
        document.getElementById('modalMembresiaLabel').textContent = 'Editar Membresía';
        new bootstrap.Modal(document.getElementById('modalMembresia')).show();
    }

    function deleteMembresia(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta membresía?')) {
            fetch(`{{ route('admin.membresias.destroy', '') }}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
    </script>
@endpush
