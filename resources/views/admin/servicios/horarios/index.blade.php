@extends('layouts.app')

@section('content')
    <!-- Encabezado de la página -->



    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Gestión de Horarios - Servicio de: {{ $servicio->nombre }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <a href="{{ route('admin.servicios.index') }}" class="btn btn-outline-primary">
                            <i class="ri-arrow-go-back-line"></i> Volver a Servicios
                        </a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Vista Semanal de Horarios -->
    <div class="card shadow-sm border-light mb-4">
        <div class="card-header">
            <h5 class="mb-0">Horarios Actuales</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach ($diasSemana as $dia)
                    <div class="col-md-4">
                        <div class="day-card p-3 rounded shadow-sm">
                            <h6 class="text-center text-primary">{{ ucfirst($dia->nombreDia) }}</h6>
                            <div id="horarios-{{ $dia->idDia }}" class="text-center">
                                @forelse ($servicio->diasSemana->where('idDia', $dia->idDia) as $horario)
                                    <div
                                        class="d-flex justify-content-between align-items-center bg-primary text-white rounded p-1 mb-2">
                                        <span>{{ $horario->pivot->horaInicio }} - {{ $horario->pivot->horaFin }}</span>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="eliminarHorario({{ $servicio->idServicio }}, {{ $dia->idDia }}, '{{ $horario->pivot->horaInicio }}')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                @empty
                                    <p class="text-muted small">Sin horarios asignados</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Formulario para asignar nuevos horarios -->
    <div class="card shadow-sm border-light mt-4">
        <div class="card-header">
            <h5 class="mb-0">Asignar Nuevos Horarios</h5>
        </div>
        <div class="card-body">
            <form id="formHorario">
                @csrf
                <div class="row g-3">
                    @foreach ($diasSemana as $dia)
                        <div class="col-md-4">
                            <div class="card p-3">
                                <div class="card-header bg-light">
                                    <input type="checkbox" name="idDia[]" value="{{ $dia->idDia }}"
                                        id="dia_{{ $dia->idDia }}" class="form-check-input me-2">
                                    <label for="dia_{{ $dia->idDia }}"
                                        class="form-check-label fw-bold">{{ ucfirst($dia->nombreDia) }}</label>
                                </div>
                                <div class="card-body horario-fields d-none mt-2">
                                    <label>Hora de Inicio:</label>
                                    <input type="time" name="horaInicio[{{ $dia->idDia }}]" class="form-control mb-2">
                                    <label>Hora de Fin:</label>
                                    <input type="time" name="horaFin[{{ $dia->idDia }}]" class="form-control">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-success mt-4" onclick="guardarHorarios()">
                    <i class="ri-save-3-line"></i> Guardar Horarios
                </button>
            </form>
            <!-- Mensaje de error o éxito -->
            <div id="mensaje" class="mt-3"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('input[type="checkbox"][name="idDia[]"]').change(function() {
                const $horarioFields = $(this).closest('.card').find('.horario-fields');
                $horarioFields.toggleClass('d-none', !this.checked);
            });
        });

        // Función para guardar horarios
        function guardarHorarios() {
            let formData = $('#formHorario').serialize();

            $.ajax({
                url: "{{ route('servicio.horarios.update', $servicio->idServicio) }}",
                method: "PUT",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.success,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let mensajeError = '<div class="alert alert-danger"><ul>';

                    $.each(errors, function(key, value) {
                        mensajeError += '<li>' + value[0] + '</li>';
                    });
                    mensajeError += '</ul></div>';

                    $('#mensaje').html(mensajeError);
                }
            });
        }

        // Función para eliminar horarios con confirmación de SweetAlert
        function eliminarHorario(idServicio, idDia, horaInicio) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este horario será eliminado permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('servicio.horario.destroy', ['servicio' => ':idServicio', 'dia' => ':idDia', 'hora' => ':horaInicio']) }}"
                            .replace(':idServicio', idServicio)
                            .replace(':idDia', idDia)
                            .replace(':horaInicio', horaInicio),
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: response.success,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el horario.',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
