@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="container">
        <h1 class="mb-4">Registro de Asistencia</h1>
    
        <div class="card mb-4">
            <div class="card-body text-center">
                <h2 id="reloj" class="display-4 font-weight-bold mb-2"></h2>
                <p id="fecha" class="lead text-muted"></p>
            </div>
        </div>
    
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Registrar Asistencia</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.asistencias.registrar') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="nombreUsuario">Nombre de Usuario:</label>
                            <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" value="{{ old('nombreUsuario') }}" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" name="accion" value="entrada" class="btn btn-info btn-wave">Registrar Entrada</button>  
                            <button type="submit" name="accion" value="salida" class="btn btn-danger btn-wave">Registrar Salida</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Tabla de asistencias -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Asistencias</h5>
            </div>
        
            <table class="table table-hover"">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Acciones</th> <!-- Nueva columna para los botones de acción -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->cliente->nombre }}</td>
                            <td>{{ $asistencia->fecha }}</td>
                            <td>{{ $asistencia->horaEntrada }}</td>
                            <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
                            <td>
                                <!-- Botón de Editar -->
                                <a href="{{ route('admin.asistencias.edit', $asistencia->idAsistencia) }}" class="btn btn-sm btn-warning">
                                    Editar
                                </a>
                                
                                <!-- Botón de Eliminar -->
                                <form action="{{ route('admin.asistencias.destroy', $asistencia->idAsistencia) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta asistencia?');">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $asistencias->links() }}
        </div>
    </div>
</div>

<script>
    function actualizarReloj() {
        var ahora = new Date();
        var hora = ahora.getHours().toString().padStart(2, '0');
        var minutos = ahora.getMinutes().toString().padStart(2, '0');
        var segundos = ahora.getSeconds().toString().padStart(2, '0');
        document.getElementById('reloj').textContent = hora + ":" + minutos + ":" + segundos;
    }

    function actualizarFecha() {
        var ahora = new Date();
        var opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var fechaFormateada = ahora.toLocaleDateString('es-ES', opcionesFecha);
        document.getElementById('fecha').textContent = fechaFormateada;
    }
    
    setInterval(actualizarReloj, 1000);
    actualizarReloj();
    actualizarFecha();
</script>
@endsection
