@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Asistencias</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Registrar</a></li>
                    <li class="breadcrumb-item active">asistencias</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Mensajes de éxito y error usando SweetAlert -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

<div class="row">
    <!-- Tarjeta para el reloj digital moderno -->
    <div class="col-lg-6 col-md-8 col-sm-12 mb-4">
    <div class="card">
        <div class="card-body text-center">
            <h5 class="card-title">Hora y Fecha Actual</h5>
            <div class="flip-clock-wrapper mb-3" style="max-width: 100%; overflow: hidden;">
                <div id="flipReloj" class="clock"></div>
            </div>
            <div>
                <p id="fecha" class="lead text-muted"></p>
            </div>
        </div>
    </div>
</div>

<!-- Añadir estos estilos en tu archivo CSS o dentro de la vista -->
<style>
    .flip-clock-wrapper {
        width: 100%;
        max-width: 500px; /* Limita el tamaño máximo del reloj */
        margin: 0 auto;
    }

    .clock {
        display: block;
        width: 100%;
        height: auto; /* Ajusta la altura automáticamente para mantener proporciones */
    }

    /* Ajuste adicional para pantallas pequeñas */
    @media (max-width: 768px) {
        .flip-clock-wrapper {
            max-width: 300px;
        }
    }
</style>


    <!-- Tarjeta para el registro manual -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Registrar Asistencia Manualmente</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.asistencias.registrar') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombreUsuario">Nombre de Usuario:</label>
                        <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" required autocomplete="off">
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" name="accion" value="entrada" class="btn btn-info btn-wave">Registrar Entrada</button>  
                        <button type="submit" name="accion" value="salida" class="btn btn-danger btn-wave">Registrar Salida</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tarjeta para escaneo de asistencia -->
   
</div>
@endsection

@push('scripts')
<!-- FlipClock.js para reloj digital moderno -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI (necesario para el autocompletado) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<link rel="stylesheet" href="https://cdn.rawgit.com/objectivehtml/FlipClock/master/compiled/flipclock.css">
<script src="https://cdn.rawgit.com/objectivehtml/FlipClock/master/compiled/flipclock.min.js"></script>



<script>
    // Inicializar FlipClock.js para reloj digital
    var clock = new FlipClock($('#flipReloj'), {
        clockFace: 'TwentyFourHourClock'
    });

    // Mostrar fecha actual
    function actualizarFecha() {
        var ahora = new Date();
        var opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var fechaFormateada = ahora.toLocaleDateString('es-ES', opcionesFecha);
        document.getElementById('fecha').textContent = fechaFormateada;
    }
    actualizarFecha();

    $(document).ready(function() {
    $("#nombreUsuario").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('admin.autocomplete.clientes') }}",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.nombreUsuario + ' (' + item.nombre + ' ' + item.apellido + ')',
                            value: item.nombreUsuario,
                            id: item.idUsuario
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $("#nombreUsuario").val(ui.item.value);
            // Opcionalmente, puedes guardar el ID del usuario seleccionado en un campo oculto
            // $("#idUsuario").val(ui.item.id);
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<div>" + item.label + "</div>")
            .appendTo(ul);
    };
});
</script>
@endpush
