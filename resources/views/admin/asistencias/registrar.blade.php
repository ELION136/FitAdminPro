@extends('layouts.admin')

@section('content')

<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Registro de Asistencia</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a>listas</a></li><i class="bi bi-three-dots-vertical"></i>
                <li aria-current="page">asistencias</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex my-xl-auto right-content align-items-center">

        <div class="mb-xl-0">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    14 Aug 2019
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate">
                    <li><a class="dropdown-item" href="javascript:void(0);">2015</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2016</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2017</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2018</a></li>
                </ul>
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
        <!-- Columna para los relojes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <h2 id="reloj" class="display-4 font-weight-bold mb-2"></h2>
                    <p id="fecha" class="lead text-muted"></p>
                    
                    <!-- Contenedor para el reloj analógico -->
                    <canvas id="relojAnalogico" width="200" height="200" class="mt-4"></canvas>
                </div>
            </div>
        </div>

        <!-- Columna para el formulario -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registrar Asistencia</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.asistencias.registrar') }}" method="POST">
                        @csrf
                    
                        <div class="form-group">
                            <label for="nombreUsuario">Nombre de Usuario:</label>
                            <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" required>
                        </div>
                        
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" name="accion" value="entrada" class="btn btn-info btn-wave">Registrar Entrada</button>  
                            <button type="submit" name="accion" value="salida" class="btn btn-danger btn-wave">Registrar Salida</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Función para el reloj digital
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

    // Función para el reloj analógico
    function dibujarReloj() {
        var canvas = document.getElementById("relojAnalogico");
        var ctx = canvas.getContext("2d");
        var radio = canvas.height / 2;
        ctx.translate(radio, radio);
        radio = radio * 0.90;

        setInterval(function() {
            dibujarEsfera(ctx, radio);
            dibujarNumeros(ctx, radio);
            dibujarHora(ctx, radio);
        }, 1000);
    }

    function dibujarEsfera(ctx, radio) {
        ctx.clearRect(-radio*1.1, -radio*1.1, radio*2.2, radio*2.2); // Limpiar el canvas antes de dibujar
        ctx.beginPath();
        ctx.arc(0, 0, radio, 0, 2 * Math.PI);
        ctx.fillStyle = "white";
        ctx.fill();
        var grad = ctx.createRadialGradient(0, 0, radio * 0.95, 0, 0, radio * 1.05);
        grad.addColorStop(0, "#333");
        grad.addColorStop(0.5, "white");
        grad.addColorStop(1, "#333");
        ctx.strokeStyle = grad;
        ctx.lineWidth = radio * 0.1;
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(0, 0, radio * 0.1, 0, 2 * Math.PI);
        ctx.fillStyle = "#333";
        ctx.fill();
    }

    function dibujarNumeros(ctx, radio) {
        var angulo;
        var numero;
        ctx.font = radio * 0.15 + "px arial";
        ctx.textBaseline = "middle";
        ctx.textAlign = "center";
        for (numero = 1; numero < 13; numero++) {
            angulo = numero * Math.PI / 6;
            ctx.rotate(angulo);
            ctx.translate(0, -radio * 0.85);
            ctx.rotate(-angulo);
            ctx.fillText(numero.toString(), 0, 0);
            ctx.rotate(angulo);
            ctx.translate(0, radio * 0.85);
            ctx.rotate(-angulo);
        }
    }

    function dibujarHora(ctx, radio) {
        var ahora = new Date();
        var hora = ahora.getHours();
        var minutos = ahora.getMinutes();
        var segundos = ahora.getSeconds();

        // Dibujar manecilla de las horas
        hora = hora % 12;
        hora = (hora * Math.PI / 6) + (minutos * Math.PI / (6 * 60)) + (segundos * Math.PI / (360 * 60));
        dibujarManecilla(ctx, hora, radio * 0.5, radio * 0.07);

        // Dibujar manecilla de los minutos
        minutos = (minutos * Math.PI / 30) + (segundos * Math.PI / (30 * 60));
        dibujarManecilla(ctx, minutos, radio * 0.8, radio * 0.07);

        // Dibujar manecilla de los segundos
        segundos = (segundos * Math.PI / 30);
        dibujarManecilla(ctx, segundos, radio * 0.9, radio * 0.02, 'red');
    }

    function dibujarManecilla(ctx, posicion, longitud, ancho, color = 'black') {
        ctx.beginPath();
        ctx.lineWidth = ancho;
        ctx.lineCap = "round";
        ctx.strokeStyle = color;
        ctx.moveTo(0, 0);
        ctx.rotate(posicion);
        ctx.lineTo(0, -longitud);
        ctx.stroke();
        ctx.rotate(-posicion);
    }

    // Iniciar el reloj analógico al cargar la página
    window.onload = dibujarReloj;
</script>

<script>
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
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $("#nombreUsuario").val(ui.item.value);
                return false;
            }
        });

        window.confirmDelete = function(url) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    });
</script>
@endpush
