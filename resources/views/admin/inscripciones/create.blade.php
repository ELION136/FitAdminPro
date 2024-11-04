@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Nueva Inscripcion </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Productos</a></li>
                        <li class="breadcrumb-item active">Inscripcion</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>



    <form id="inscripcionForm" action="{{ route('admin.inscripciones.store') }}" method="POST">
        @csrf

        <!-- Contenedor principal con dos columnas -->
        <div class="row">
            <!-- Columna izquierda: Detalle de la Venta -->
            <div class="col-md-6">
                <!-- Datos de la Inscripción -->
                <div class="card mb-4">
                    <div class="card-header">
                        Datos de la Inscripción
                    </div>
                    <div class="card-body">
                        <!-- Cliente -->
                        <div class="mb-3 d-flex align-items-end">
                            <div class="flex-grow-1 me-2">
                                <label for="idCliente" class="form-label">Cliente</label>
                                <select name="idCliente" class="form-control" id="idCliente" required></select>
                            </div>
                            <button type="button" class="btn btn-primary" id="btnAgregarCliente" data-bs-toggle="modal"
                                data-bs-target="#modalAgregarCliente">
                                <i class="las la-plus"></i> Añadir Cliente
                            </button>
                        </div>

                        <!-- Tipo de Producto -->
                        <div class="mb-3">
                            <label class="form-label">Tipo de Producto</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input type="radio" id="tipoMembresia" name="tipoProducto" value="membresia"
                                        class="form-check-input" required>
                                    <label class="form-check-label" for="tipoMembresia">Membresía</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="tipoServicio" name="tipoProducto" value="servicio"
                                        class="form-check-input">
                                    <label class="form-check-label" for="tipoServicio">Servicio</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card para Detalle de Venta -->
                <div id="detalleVenta" class="mb-4">
                    <div class="card">
                        <div class="card-header">
                            Detalle de la Venta
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio Unitario (Bs)</th>
                                            <th>Descuento (%)</th>
                                            <th>Precio Final (Bs)</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalleProductos">
                                        <!-- Productos seleccionados se mostrarán aquí -->
                                    </tbody>
                                </table>
                            </div>
                            <!-- Mostrar el total calculado -->
                            <div class="mt-3 text-center">
                                <h4>Total a Pagar: Bs<span id="totalPagar">0.00</span></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón para registrar la inscripción -->
                <button type="submit" class="btn btn-success btn-block mt-4">Registrar Inscripción</button>
            </div>

            <!-- Columna derecha: Selección de productos -->
            <div class="col-md-6">
                <!-- Selección de Membresías y Servicios -->
                <div class="card mb-4">
                    <div class="card-header">
                        Seleccionar Productos
                    </div>
                    <div class="card-body">
                        <!-- Contenedor de Membresías -->
                        <div id="membresiasContainer" style="display: none;">
                            <h5>Selecciona una Membresía</h5>
                            <div class="list-group">
                                @foreach ($membresias as $membresia)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $membresia->nombre }}</h6>
                                            <p class="mb-1">{{ $membresia->descripcion }}</p>
                                            <small>Precio: Bs{{ number_format($membresia->precio, 2) }}</small>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm btnAgregarProducto"
                                            data-tipo="membresia" data-id="{{ $membresia->idMembresia }}"
                                            data-nombre="{{ $membresia->nombre }}" data-precio="{{ $membresia->precio }}">
                                            <i class="las la-plus"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Contenedor de Servicios -->
                        <div id="serviciosContainer" style="display: none;">
                            <h5>Selecciona Servicios</h5>
                            <div class="list-group">
                                @foreach ($servicios as $servicio)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $servicio->nombre }}</h6>
                                            <p class="mb-1">{{ $servicio->descripcion }}</p>
                                            <p class="mb-1">
                                                @foreach ($servicio->diasHorarios as $diaHorario)
                                                    {{ ucfirst($diaHorario->diaSemana->nombreDia) }}:
                                                    {{ date('H:i', strtotime($diaHorario->horaInicio)) }} -
                                                    {{ date('H:i', strtotime($diaHorario->horaFin)) }}<br>
                                                @endforeach
                                            </p>
                                            <small>Precio: Bs{{ number_format($servicio->precioTotal, 2) }}</small>
                                            <small>Capacidad: {{ $servicio->capacidad }}</small>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm btnAgregarProducto"
                                            data-tipo="servicio" data-id="{{ $servicio->idServicio }}"
                                            data-nombre="{{ $servicio->nombre }}"
                                            data-precio="{{ $servicio->precioTotal }}"
                                            data-capacidad="{{ $servicio->capacidad }}">

                                            <i class="las la-plus"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Eliminamos enctype ya que no se manejarán archivos -->
                <form id="formAgregarCliente">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarClienteLabel">Añadir Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Campos obligatorios para agregar un nuevo cliente -->
                        <div class="mb-3">
                            <label for="nombreCliente" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreCliente" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="primerApellidoCliente" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" id="primerApellidoCliente" name="primerApellido"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="fechaNacimientoCliente" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimientoCliente"
                                name="fechaNacimiento" required>
                        </div>
                        <div class="mb-3">
                            <label for="generoCliente" class="form-label">Género</label>
                            <select class="form-control" id="generoCliente" name="genero" required>
                                <option value="">Seleccione una opción</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <!-- Puedes agregar más campos opcionales si lo deseas -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jsPDF -->

    <!-- SweetAlert -->

    <!-- Script personalizado -->
    <script>
        $(document).ready(function() {
            // Inicialización de variables
            var productosSeleccionados = [];
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Búsqueda de clientes en tiempo real
            $('#idCliente').select2({
                ajax: {
                    url: '{{ route('admin.inscripciones.searchCliente') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                placeholder: 'Buscar cliente...',
                minimumInputLength: 1,
            });
            // Manejo del formulario de agregar cliente en el modal
            $('#formAgregarCliente').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('admin.inscripciones.storeCliente') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Cerrar el modal y agregar el cliente al select2
                        $('#modalAgregarCliente').modal('hide');
                        var newOption = new Option(response.text, response.id, true, true);
                        $('#idCliente').append(newOption).trigger('change');
                        // Limpiar el formulario
                        $('#formAgregarCliente')[0].reset();
                    },
                    error: function(xhr) {
                        // Mostrar errores
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage
                        });
                    }
                });
            });

            // Mostrar contenedores según el tipo de producto
            $('input[name="tipoProducto"]').change(function() {
                if ($(this).val() == 'membresia') {
                    $('#membresiasContainer').show();
                    $('#serviciosContainer').hide();
                } else if ($(this).val() == 'servicio') {
                    $('#serviciosContainer').show();
                    $('#membresiasContainer').hide();
                }
                productosSeleccionados = [];
                mostrarDetalleVenta();
                calcularTotal();
            });

            // Agregar producto al detalle de venta
            $('.btnAgregarProducto').click(function() {
                let tipoProducto = $(this).data('tipo');
                let idProducto = $(this).data('id');
                let nombre = $(this).data('nombre');
                let precio = parseFloat($(this).data('precio'));
                let capacidad = parseInt($(this).data('capacidad'));


                if (tipoProducto === 'servicio' && capacidad <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Servicio lleno',
                        text: 'Este servicio ya está lleno y no puede ser seleccionado.'
                    });
                    return; // Detener el proceso si la capacidad es 0
                }

                // Verificar si ya hay una membresía agregada
                if (tipoProducto == 'membresia') {
                    let existeMembresia = productosSeleccionados.find(p => p.tipoProducto == 'membresia');
                    if (existeMembresia) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Membresía ya agregada',
                            text: 'Solo puede agregar una membresía al detalle de venta.',
                        });
                        return;
                    }
                }

                // Verificar si el producto ya está agregado
                let productoExistente = productosSeleccionados.find(p => p.idProducto == idProducto && p
                    .tipoProducto == tipoProducto);
                if (productoExistente) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto ya agregado',
                        text: 'Este producto ya está en el detalle de venta.',
                    });
                } else {
                    productosSeleccionados.push({
                        tipoProducto: tipoProducto,
                        idProducto: idProducto,
                        nombre: nombre,
                        precio: precio,
                        descuento: 0
                    });
                    mostrarDetalleVenta();
                    calcularTotal();
                }
            });

            // Mostrar detalle de la venta con descuentos editables
            function mostrarDetalleVenta() {
                let tbody = $('#detalleProductos');
                tbody.empty();
                if (productosSeleccionados.length > 0) {
                    $('#detalleVenta').show();
                    productosSeleccionados.forEach(function(producto, index) {
                        let precioFinal = producto.precio - (producto.precio * (producto.descuento || 0) /
                            100);
                        let row = '<tr>' +
                            '<td>' + producto.nombre + '</td>' +
                            '<td>Bs' + producto.precio.toFixed(2) + '</td>' +
                            '<td><input type="number" min="0" max="100" step="1" class="form-control descuento-input" data-index="' +
                            index + '" value="' + (producto.descuento || 0) + '"></td>' +
                            '<td class="precio-final">Bs' + precioFinal.toFixed(2) + '</td>' +
                            '<td><button type="button" class="btn btn-danger btn-sm btnEliminarProducto" data-index="' +
                            index + '"><i class="las la-trash"></i></button></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    $('#detalleVenta').hide();
                }
            }

            // Evento para actualizar descuento y recalcular total
            $(document).on('input', '.descuento-input', function() {
                let index = $(this).data('index');
                let descuento = parseFloat($(this).val()) || 0;

                if (descuento < 0 || descuento > 100) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Descuento inválido',
                        text: 'El descuento debe estar entre 0% y 100%.',
                    });
                    $(this).val(productosSeleccionados[index].descuento);
                    return;
                }

                productosSeleccionados[index].descuento = descuento;
                let precio = productosSeleccionados[index].precio;
                let precioFinal = precio - (precio * descuento / 100);
                $(this).closest('tr').find('.precio-final').text('Bs' + precioFinal.toFixed(2));
                calcularTotal();
            });

            // Eliminar producto del detalle de venta
            $(document).on('click', '.btnEliminarProducto', function() {
                let index = $(this).data('index');
                productosSeleccionados.splice(index, 1);
                mostrarDetalleVenta();
                calcularTotal();
            });

            // Calcular el total a pagar
            function calcularTotal() {
                let total = 0;
                productosSeleccionados.forEach(function(producto) {
                    let precioFinal = producto.precio - (producto.precio * (producto.descuento || 0) / 100);
                    total += precioFinal;
                });
                $('#totalPagar').text(total.toFixed(2));
            }



            $('#inscripcionForm').submit(function(event) {
                event.preventDefault();

                if (productosSeleccionados.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar al menos un producto.'
                    });
                    return false;
                }

                let productosInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'productos',
                    value: JSON.stringify(productosSeleccionados)
                });
                $(this).append(productosInput);

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Inscripción realizada correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            const url = new URL(
                                '{{ route('admin.inscripciones.generarPDF', ':id') }}'
                                .replace(':id', response.comprobanteId), window
                                .location.origin);
                            window.open(url, '_blank', 'width=800,height=600');
                        });

                        $('#inscripcionForm')[0].reset();
                        productosSeleccionados = [];
                        mostrarDetalleVenta();
                        calcularTotal();
                        productosInput.remove();
                    },
                    error: function(xhr) {
                        let errorMessage = '';

                        // Manejar diferentes tipos de respuestas de error
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                // Si hay un mensaje general de error
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                // Si hay múltiples errores de validación
                                Object.values(xhr.responseJSON.errors).forEach(function(
                                    errors) {
                                    errors.forEach(function(error) {
                                        errorMessage += error + '\n';
                                    });
                                });
                            }
                        } else {
                            // Si no hay respuesta JSON
                            errorMessage = 'Ha ocurrido un error al procesar la inscripción.';
                        }

                        // Mostrar el mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la inscripción',
                            text: errorMessage,
                            confirmButtonText: 'Entendido'
                        });

                        productosInput.remove();
                    }
                });
            });
        });
    </script>
@endpush
