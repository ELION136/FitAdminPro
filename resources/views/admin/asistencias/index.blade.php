@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Asistencia Manual</h1>

        <!-- Reloj Digital y Fecha Literal -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6 text-center">
                <div class="reloj-digital">
                    <h2 id="relojDigital" class="display-4"></h2>
                    <h4 id="fechaLiteral"></h4>
                </div>
            </div>
        </div>

        <!-- Select2 para buscar cliente -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <label for="clienteSelect" class="form-label">Buscar Cliente:</label>
                <select id="clienteSelect" class="form-control"></select>
            </div>
        </div>

        <!-- Mostrar tarjeta con detalles del cliente seleccionado -->
        <div id="resultadoCliente" class="row justify-content-center mt-4"></div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar Select2 con AJAX para búsqueda de clientes
            $('#clienteSelect').select2({
                placeholder: 'Escriba el nombre del cliente',
                ajax: {
                    url: '{{ route('admin.asistencias.buscar') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // término de búsqueda
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(cliente) {
                                return {
                                    id: cliente.idCliente,
                                    text: cliente.nombre + ' ' + cliente.primerApellido
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });

            // Evento cuando se selecciona un cliente
            $('#clienteSelect').on('select2:select', function(e) {
                let clienteId = e.params.data.id;

                // Realizar una llamada AJAX para obtener detalles del cliente seleccionado
                $.ajax({
                    url: '{{ route('admin.asistencias.buscar') }}',
                    type: 'GET',
                    data: {
                        id: clienteId
                    },
                    success: function(cliente) {
                        if (cliente && cliente.inscripciones && cliente.inscripciones.length > 0) {
                            // Verificar si tiene una inscripción activa
                            let inscripcionActiva = cliente.inscripciones[0]; // Suponemos que siempre se obtiene la inscripción activa

                            // Verificar si detalle_inscripciones existe
                            if (inscripcionActiva.detalle_inscripciones && Array.isArray(inscripcionActiva.detalle_inscripciones)) {
                                // Buscar el detalle de membresía
                                let detalleMembresia = inscripcionActiva.detalle_inscripciones.find(function(detalle) {
                                    return detalle.tipoProducto === 'membresia';
                                });

                                let membresiaNombre = detalleMembresia && detalleMembresia.membresia ? detalleMembresia.membresia.nombre : 'No tiene membresía activa';
                                
                                // Días restantes y estado de la inscripción
                                let diasRestantes = inscripcionActiva.diasRestantes !== null ? inscripcionActiva.diasRestantes : 'No disponible';
                                let estadoInscripcion = inscripcionActiva.estado === 'activa' ? 'Activa' : 'Inactiva';

                                // Determinar la ruta de la imagen
                                let imageUrl = cliente.image ? `/storage/${cliente.image}` : `/images/default-profile.png`;

                                let tarjetaCliente = `
                                    <div class="col-md-8">
                                        <div class="card mb-3">
                                            <div class="row no-gutters">
                                                <div class="col-md-4">
                                                    <img src="${imageUrl}" class="card-img" alt="${cliente.nombre}">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title">${cliente.nombre} ${cliente.primerApellido}</h5>
                                                        <p class="card-text"><strong>Membresía activa:</strong> ${membresiaNombre}</p>
                                                        <p class="card-text"><strong>Días Restantes:</strong> ${diasRestantes}</p>
                                                        <p class="card-text"><strong>Estado:</strong> ${estadoInscripcion}</p>
                                                        <form method="POST" action="{{ route('admin.asistencias.registrar') }}">
                                                            @csrf
                                                            <input type="hidden" name="idCliente" value="${cliente.idCliente}">
                                                            <input type="hidden" name="idInscripcion" value="${inscripcionActiva.idInscripcion}">
                                                            <button type="submit" class="btn btn-success">Registrar Asistencia</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('#resultadoCliente').html(tarjetaCliente);
                            } else {
                                $('#resultadoCliente').html('<p class="text-center text-warning">No se encontraron detalles de membresía.</p>');
                            }
                        } else {
                            $('#resultadoCliente').html('<p class="text-center text-warning">Este cliente no tiene inscripciones activas.</p>');
                        }
                    },
                    error: function() {
                        $('#resultadoCliente').html('<p class="text-center text-danger">Error al obtener los datos del cliente.</p>');
                    }
                });
            });

            // Función para actualizar el reloj digital y la fecha literal
            function actualizarRelojYFecha() {
                const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

                let ahora = new Date();
                let hora = String(ahora.getHours()).padStart(2, '0');
                let minutos = String(ahora.getMinutes()).padStart(2, '0');
                let segundos = String(ahora.getSeconds()).padStart(2, '0');

                // Reloj digital en formato HH:MM:SS
                document.getElementById('relojDigital').textContent = `${hora}:${minutos}:${segundos}`;

                // Fecha literal en formato "Día, DD de Mes de YYYY"
                let diaSemana = diasSemana[ahora.getDay()];
                let dia = ahora.getDate();
                let mes = meses[ahora.getMonth()];
                let anio = ahora.getFullYear();
                document.getElementById('fechaLiteral').textContent = `${diaSemana}, ${dia} de ${mes} de ${anio}`;
            }

            // Inicializar la fecha literal y el reloj digital
            actualizarRelojYFecha();
            setInterval(actualizarRelojYFecha, 1000);

            // SweetAlert para mensajes de éxito/error
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '{{ session('success') }}',
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                });
            @endif
        });
    </script>
@endpush


@endsection
