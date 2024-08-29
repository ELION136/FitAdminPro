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
                            <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" name="accion" value="entrada" class="btn btn-info btn-wave">Registrar Entrada</button>  
                            <button type="submit" name="accion" value="salida" class="btn btn-danger btn-wave">Registrar Salida</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir jQuery -->


@endsection
@push('scripts')
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
