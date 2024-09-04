<!-- recursos/views/horarios/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Asignar Servicios a Horarios</h2>

        <!-- Selección de Entrenador -->
        <div class="form-group">
            <label for="entrenador">Seleccionar Entrenador:</label>
            <select id="entrenador" class="form-control">
                <option value="">Seleccione un entrenador</option>
                @foreach ($entrenadores as $entrenador)
                    <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}
                        {{ $entrenador->primerApellido }}</option>
                @endforeach
            </select>
        </div>

        <!-- Calendario -->
        <div id="calendar"></div>

        <!-- Modal para asignar servicio -->
        <div class="modal fade" id="asignarServicioModal" tabindex="-1" role="dialog" aria-labelledby="asignarServicioLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="asignarServicioLabel">Asignar Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="asignarServicioForm" method="POST"
                            action="{{ route('admin.horarios.asignarServicio') }}">
                            @csrf
                            <input type="hidden" name="idHorario" id="idHorario">

                            <div class="form-group">
                                <label for="servicio">Seleccionar Servicio:</label>
                                <select name="idServicio" id="servicio" class="form-control">
                                    @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio->idServicio }}">{{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Asignar Servicio</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(fetchInfo, successCallback, failureCallback) {
                    var entrenadorId = document.getElementById('entrenador').value;

                    if (entrenadorId) {
                        fetch(`/admin/horarios/${entrenadorId}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log(data); // Verifica los datos recibidos
                                var events = data.map(horario => {
                                    return {
                                        id: horario.idHorario,
                                        title: `Horario: ${horario.horaInicio} - ${horario.horaFin}`,
                                        start: horario.dia + 'T' + horario.horaInicio,
                                        end: horario.dia + 'T' + horario.horaFin,
                                    };
                                });
                                successCallback(events);
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                            });
                    }
                },
                dateClick: function(info) {
                    // Abrir modal para asignar servicio
                    document.getElementById('idHorario').value = info.event.id;
                    $('#asignarServicioModal').modal('show');
                }
            });

            calendar.render();

            document.getElementById('entrenador').addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
@endpush
