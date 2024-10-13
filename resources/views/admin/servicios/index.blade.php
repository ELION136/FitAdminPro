@extends('layouts.app')

@section('content')

    <!-- Page title and breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Gestión de Servicios</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Servicios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para añadir servicio -->
    <div class="row mb-3">
        <div class="col-sm-auto">
            <div class="d-flex flex-wrap align-items-start gap-2">
                <button class="btn btn-success add-btn" onclick="createServicio()"><i class="ri-add-line align-bottom me-1"></i> Añadir Servicio</button>
            </div>
        </div>
    </div>

    <!-- Tabla de Servicios -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Servicios</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservaTable" class="table table-bordered text-nowrap w-100">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Duración</th>
                                    <th>Capacidad Máxima</th>
                                    <th>Precio por Sección (BOB)</th>
                                    <th>Precio de entrada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>
                                            @php
                                                $horas = floor($servicio->duracion / 60);
                                                $minutos = $servicio->duracion % 60;
                                            @endphp
                                            {{ $horas > 0 ? $horas . ' hora' . ($horas > 1 ? 's' : '') : '' }}
                                            {{ $minutos > 0 ? $minutos . ' minuto' . ($minutos > 1 ? 's' : '') : '' }}
                                        </td>
                                        <td>{{ $servicio->capacidadMaxima }}</td>
                                        <td>{{ number_format($servicio->precioPorSeccion, 2, '.', ',') }} BOB</td>
                                        <td>{{ $servicio->incluyeCostoEntrada ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary btn-sm" onclick="editServicio({{ $servicio }})"><i class="ri-pencil-fill"></i></button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteServicio({{ $servicio->idServicio }})"><i class="ri-delete-bin-fill"></i></button>
                                            </div>
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
</div>

<!-- Modal Crear/Editar Servicio -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Cambié el tamaño del modal a modal-lg para más espacio -->
        <div class="modal-content">
            <form id="formServicio" method="POST">
                @csrf
                <input type="hidden" id="servicioId" name="servicioId">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="modalServicioLabel">Añadir/Editar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="capacidadMaxima" class="form-label">Capacidad Máxima</label>
                                <input type="number" class="form-control" id="capacidadMaxima" name="capacidadMaxima" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duracion" class="form-label">Duración (minutos)</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" required min="60" max="1440">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="precioPorSeccion" class="form-label">Precio por Sección (BOB)</label>
                                <input type="number" step="0.01" class="form-control" id="precioPorSeccion" name="precioPorSeccion" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="incluyeCostoEntrada" name="incluyeCostoEntrada" value="1">
                                <label class="form-check-label" for="incluyeCostoEntrada">Incluye Costo Entrada</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formServicio');
        
        function createServicio() {
            form.reset();
            document.getElementById('servicioId').value = '';
            document.getElementById('modalServicioLabel').textContent = 'Añadir Servicio';
            new bootstrap.Modal(document.getElementById('modalServicio')).show();
        }

        function editServicio(servicio) {
            form.reset();
            document.getElementById('servicioId').value = servicio.idServicio;
            document.getElementById('nombre').value = servicio.nombre;
            document.getElementById('descripcion').value = servicio.descripcion;
            document.getElementById('duracion').value = servicio.duracion;
            document.getElementById('capacidadMaxima').value = servicio.capacidadMaxima;
            document.getElementById('precioPorSeccion').value = servicio.precioPorSeccion;
            document.getElementById('incluyeCostoEntrada').checked = servicio.incluyeCostoEntrada;
            document.getElementById('modalServicioLabel').textContent = 'Editar Servicio';
            new bootstrap.Modal(document.getElementById('modalServicio')).show();
        }

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
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
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
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el servicio.',
                                confirmButtonText: 'Aceptar'
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

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let servicioId = document.getElementById('servicioId').value;
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
                        text: data.success,
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

        window.createServicio = createServicio;
        window.editServicio = editServicio;
        window.deleteServicio = deleteServicio;
    });
    $(document).ready(function() {
            $('#reservaTable').DataTable({
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5,
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
</script>
@endpush
