@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Servicios</h4>
                <button class="btn btn-primary" onclick="createServicio()">Añadir Servicio</button>
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
                                <th>Capacidad Máxima</th>
                                <th>Precio por Sección (BOB)</th>
                                <th>Incluye Costo Entrada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre }}</td>
                                    <td>{{ $servicio->descripcion }}</td>
                                    <td>{{ $servicio->capacidadMaxima }}</td>
                                    <td>{{ number_format($servicio->precioPorSeccion, 2, '.', ',') }} BOB</td>
                                    <td>{{ $servicio->incluyeCostoEntrada ? 'Sí' : 'No' }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="editServicio({{ $servicio }})">Editar</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteServicio({{ $servicio->idServicio }})">Eliminar</button>
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

<!-- Modal Crear/Editar Servicio -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formServicio" method="POST">
                @csrf
                <input type="hidden" id="servicioId" name="servicioId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalServicioLabel">Añadir/Editar Servicio</h5>
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
                        <label for="capacidadMaxima">Capacidad Máxima</label>
                        <input type="number" class="form-control" id="capacidadMaxima" name="capacidadMaxima" required>
                    </div>
                    <div class="form-group">
                        <label for="precioPorSeccion">Precio por Sección (BOB)</label>
                        <input type="number" step="0.01" class="form-control" id="precioPorSeccion" name="precioPorSeccion" required>
                    </div>
                    <div class="form-group">
                        <label for="incluyeCostoEntrada">Incluye Costo Entrada</label>
                        <input type="checkbox" id="incluyeCostoEntrada" name="incluyeCostoEntrada" value="1">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let form = document.getElementById('formServicio');

    // Función para abrir el modal de creación de un nuevo servicio
    function createServicio() {
        form.reset();
        form.servicioId.value = '';
        document.getElementById('modalServicioLabel').textContent = 'Añadir Servicio';
        new bootstrap.Modal(document.getElementById('modalServicio')).show();
    }

    // Función para abrir el modal de edición de un servicio existente
    function editServicio(servicio) {
        form.reset();
        form.servicioId.value = servicio.idServicio;
        form.nombre.value = servicio.nombre;
        form.descripcion.value = servicio.descripcion;
        form.capacidadMaxima.value = servicio.capacidadMaxima;
        form.precioPorSeccion.value = servicio.precioPorSeccion;
        form.incluyeCostoEntrada.checked = servicio.incluyeCostoEntrada;
        document.getElementById('modalServicioLabel').textContent = 'Editar Servicio';
        new bootstrap.Modal(document.getElementById('modalServicio')).show();
    }

    // Crear o Editar Servicio
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let servicioId = form.servicioId.value;
        let url = servicioId ? `{{ route('admin.servicios.update', '') }}/${servicioId}` : `{{ route('admin.servicios.store') }}`;
        let formData = new FormData(form);

        if (servicioId) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
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
                    text: 'Servicio guardado correctamente',
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

    // Función para eliminar un servicio
    function deleteServicio(id) {
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
                fetch(`{{ route('admin.servicios.destroy', '') }}/${id}`, {
                    method: 'POST',
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
                            text: 'El servicio ha sido eliminado correctamente',
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
