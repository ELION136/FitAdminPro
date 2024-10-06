{{-- resources/views/membresias/index.blade.php --}}
@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Catálogo de Membresías</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">catalogo</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($membresias as $membresia)
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box mb-4">
                    <div class="card-body bg-light m-2 p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fw-semibold">{{ $membresia->nombre }}</h5>
                            </div>
                            <div class="ms-auto">
                                <h2 class="month mb-0">Bs.{{ number_format($membresia->precio, 2) }} <small class="fs-13 text-muted">/meses</small></h2>
                                <h2 class="annual mb-0"><small class="fs-16"><del>Bs.{{ number_format($membresia->precio * 12, 2) }}</del></small> ${{ number_format($membresia->precio * 9, 2) }} <small class="fs-13 text-muted">/Año</small></h2>
                            </div>
                        </div>

                        <p class="text-muted">{{ $membresia->descripcion }}</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $membresia->duracion }}</b> días de acceso
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Acceso: <b>{{ $membresia->diasAcceso }}</b>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Beneficios adicionales: <b>{{ $membresia->beneficios }}</b>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2">
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#solicitarModal" data-id="{{ $membresia->idMembresia }}">
                                Solicitar Membresía
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Modal para solicitar membresía --}}
<div class="modal fade" id="solicitarModal" tabindex="-1" aria-labelledby="solicitarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="solicitarModalLabel">Solicitar Membresía</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('cliente.membresias.solicitar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="idMembresia" id="idMembresia">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#solicitarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var idMembresia = button.data('id'); // Extraer la información de datos
        var modal = $(this);
        modal.find('#idMembresia').val(idMembresia); // Asignar el ID de la membresía al input oculto
    });
</script>

@endsection
