@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1 class="my-4">Reporte de Asistencias</h1>

        <!-- Filtros -->
        <form method="GET" action="{{ route('admin.reportes.asistencias') }}">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="idCliente">Cliente</label>
                    <select class="form-control select2-ajax" name="idCliente" id="idCliente">
                        <option value="">Seleccionar cliente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fechaInicio">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fechaInicio" value="{{ request('fechaInicio') }}">
                </div>
                <div class="col-md-3">
                    <label for="fechaFin">Fecha Fin</label>
                    <input type="date" class="form-control" name="fechaFin" value="{{ request('fechaFin') }}">
                </div>
                <div class="col-md-3">
                    <label for="horaEntrada">Hora Entrada</label>
                    <input type="time" class="form-control" name="horaEntrada" value="{{ request('horaEntrada') }}">
                </div>
                <div class="col-md-3">
                    <label for="horaSalida">Hora Salida</label>
                    <input type="time" class="form-control" name="horaSalida" value="{{ request('horaSalida') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <!-- Tabla de Resultados -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asistencias as $asistencia)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $asistencia->cliente->nombre }}</td>
                        <td>{{ $asistencia->fecha }}</td>
                        <td>{{ $asistencia->horaEntrada }}</td>
                        <td>{{ $asistencia->horaSalida }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botones de Exportación -->
        <div class="mt-3">
            <a href="{{ route('admin.asistencias.reporte_pdf', request()->query()) }}" class="btn btn-success">Exportar a
                Excel</a>
            <a href="{{ route('admin.asistencias.reporte_excel', request()->query()) }}" class="btn btn-danger">Exportar a
                PDF</a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-ajax').select2({
            placeholder: 'Seleccionar cliente',
            ajax: {
                url: '{{ route("clientes.search") }}',  // Ruta para buscar clientes
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (cliente) {
                            return {
                                id: cliente.id,
                                text: cliente.nombre
                            };
                        })
                    };
                },
                cache: true
            },
            allowClear: true
        });
    });
</script>
@endpush