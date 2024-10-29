@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Nueva Inscripción</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->any() ? $errors->all() : [] as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                });
            </script>
        @endif

        <form id="inscripcionForm" action="{{ route('admin.inscripciones.store') }}" method="POST">
            @csrf

            <!-- Card para selección de cliente y tipo de producto -->
            <div class="card mb-4">
                <div class="card-header">
                    Datos de la Inscripción
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="idCliente">Cliente</label>
                        <select name="idCliente" class="form-control" id="idCliente" required></select>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Producto</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" id="tipoMembresia" name="tipoProducto" value="membresia"
                                    class="custom-control-input" required>
                                <label class="custom-control-label" for="tipoMembresia">Membresía</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="tipoServicio" name="tipoProducto" value="servicio"
                                    class="custom-control-input">
                                <label class="custom-control-label" for="tipoServicio">Servicio</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card para Membresías -->
            <div id="membresiasContainer" class="mb-4" style="display: none;">
                <h3 class="mt-4">Selecciona una Membresía</h3>
                <div class="card-deck">
                    @foreach ($membresias as $membresia)
                        <div class="card membresia-card" style="cursor: pointer;" data-id="{{ $membresia->idMembresia }}"
                            data-precio="{{ $membresia->precio }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $membresia->nombre }}</h5>
                                <p class="card-text">{{ $membresia->descripcion }}</p>
                                <p class="card-text"><strong>Precio:</strong> Bs{{ number_format($membresia->precio, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="idMembresia" id="idMembresia">
            </div>

            <!-- Card para Servicios -->
            <div id="serviciosContainer" class="mb-4" style="display: none;">
                <h3 class="mt-4">Selecciona Servicios</h3>
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                            data-target="#serviciosModal">Elegir Servicios</button>
                        <!-- Mostrar servicios seleccionados -->
                        <div id="serviciosSeleccionados" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Modal para Selección de Servicios -->
            <div class="modal fade" id="serviciosModal" tabindex="-1" role="dialog" aria-labelledby="serviciosModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="serviciosModalLabel">Selecciona los Servicios</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="tablaServicios">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Seleccionar</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Precio (Bs)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($servicios as $servicio)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="servicio-checkbox"
                                                        data-id="{{ $servicio->idServicio }}"
                                                        data-nombre="{{ $servicio->nombre }}"
                                                        data-precio="{{ $servicio->precioTotal }}">
                                                </td>
                                                <td>{{ $servicio->nombre }}</td>
                                                <td>{{ $servicio->descripcion }}</td>
                                                <td>Bs{{ number_format($servicio->precioTotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="btnAgregarServicios">Agregar
                                Servicios</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card para Detalle de Venta -->
            <div id="detalleVenta" class="mb-4" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        Detalle de la Venta
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Unitario (Bs)</th>
                                        <th>Descuento (%)</th>
                                        <th>Precio Final (Bs)</th>
                                    </tr>
                                </thead>
                                <tbody id="detalleProductos">
                                    <!-- Productos seleccionados se mostrarán aquí -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Botón para aplicar descuentos -->
                        <button type="button" class="btn btn-info" id="btnAplicarDescuentos">Aplicar Descuentos</button>
                    </div>
                </div>
            </div>

            <!-- Mostrar el total calculado -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h3>Total a Pagar: Bs<span id="totalPagar">0.00</span></h3>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-block">Registrar Inscripción</button>
        </form>
    </div>

    <!-- Incluir jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    <!-- Incluir Bootstrap JS si aún no lo tienes -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
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

            // Mostrar contenedores según el tipo de producto
            $('input[name="tipoProducto"]').change(function() {
                if ($(this).val() == 'membresia') {
                    $('#membresiasContainer').show();
                    $('#serviciosContainer').hide();
                    $('#detalleVenta').hide();
                    $('#serviciosSeleccionados').empty();
                    resetServicios();
                    calcularTotal();
                } else if ($(this).val() == 'servicio') {
                    $('#serviciosContainer').show();
                    $('#membresiasContainer').hide();
                    $('#detalleVenta').hide();
                    $('#idMembresia').val('');
                    $('.membresia-card').removeClass('border-primary');
                    calcularTotal();
                }
            });

            // Selección de membresía
            var productosSeleccionados = [];
            $('.membresia-card').click(function() {
                $('.membresia-card').removeClass('border-primary');
                $(this).addClass('border-primary');
                let idMembresia = $(this).data('id');
                let nombre = $(this).find('.card-title').text();
                let precio = parseFloat($(this).data('precio'));
                $('#idMembresia').val(idMembresia);
                productosSeleccionados = [];
                productosSeleccionados.push({
                    tipoProducto: 'membresia',
                    idProducto: idMembresia,
                    nombre: nombre,
                    precio: precio,
                    descuento: 0,
                    tipoDescuento: 'ninguno'
                });
                mostrarDetalleVenta();
                calcularTotal();
            });

            // Manejo de servicios en el modal
            var serviciosSeleccionadosData = {};

            $('#btnAgregarServicios').click(function() {
                productosSeleccionados = [];
                $('.servicio-checkbox:checked').each(function() {
                    let id = $(this).data('id');
                    let nombre = $(this).data('nombre');
                    let precio = parseFloat($(this).data('precio'));
                    productosSeleccionados.push({
                        tipoProducto: 'servicio',
                        idProducto: id,
                        nombre: nombre,
                        precio: precio,
                        descuento: 0,
                        tipoDescuento: 'ninguno',
                        extras: null,
                        precioExtras: 0
                    });
                });
                $('#serviciosModal').modal('hide');
                mostrarDetalleVenta();
                calcularTotal();
            });

            // Función para resetear servicios seleccionados
            function resetServicios() {
                $('.servicio-checkbox').prop('checked', false);
                serviciosSeleccionadosData = {};
                productosSeleccionados = [];
            }

            // Mostrar detalle de la venta
            function mostrarDetalleVenta() {
                if (productosSeleccionados.length > 0) {
                    $('#detalleVenta').show();
                    let tbody = $('#detalleProductos');
                    tbody.empty();
                    productosSeleccionados.forEach(function(producto, index) {
                        let precioFinal = producto.precio - (producto.precio * (producto.descuento || 0) /
                            100);
                        let row = '<tr>' +
                            '<td>' + producto.nombre + '</td>' +
                            '<td>Bs' + producto.precio.toFixed(2) + '</td>' +
                            '<td>' + (producto.descuento || 0) + '%</td>' +
                            '<td>Bs' + precioFinal.toFixed(2) + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    $('#detalleVenta').hide();
                }
            }

            // Calcular el total a pagar
            function calcularTotal() {
                let total = 0;
                productosSeleccionados.forEach(function(producto) {
                    let precioFinal = producto.precio - (producto.precio * (producto.descuento || 0) / 100);
                    total += precioFinal;
                });
                $('#totalPagar').text(total.toFixed(2));
            }

            // Botón para aplicar descuentos
            $('#btnAplicarDescuentos').click(function() {
                let index = 0;

                function solicitarDescuento() {
                    if (index < productosSeleccionados.length) {
                        let producto = productosSeleccionados[index];
                        Swal.fire({
                            title: 'Aplicar Descuento a ' + producto.nombre,
                            html: '<select id="descuentoSelect" class="swal2-input">' +
                                '<option value="0">0%</option>' +
                                '<option value="5">5%</option>' +
                                '<option value="10">10%</option>' +
                                '<option value="20">20%</option>' +
                                '</select>',
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            preConfirm: () => {
                                return {
                                    descuento: parseFloat(document.getElementById(
                                        'descuentoSelect').value) || 0,
                                    tipoDescuento: 'promocion' // Puedes ajustar esto si necesitas tipos diferentes
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                producto.descuento = result.value.descuento;
                                producto.tipoDescuento = result.value.tipoDescuento;
                                index++;
                                solicitarDescuento();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                index++;
                                solicitarDescuento();
                            } else {
                                // Do nothing
                            }
                        });
                    } else {
                        mostrarDetalleVenta();
                        calcularTotal();
                    }
                }
                solicitarDescuento();
            });

            // Al enviar el formulario, recopilar los datos necesarios
            $('#inscripcionForm').submit(function(event) {
                if (productosSeleccionados.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar al menos un producto.'
                    });
                    event.preventDefault();
                    return false;
                }

                let productosInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'productos',
                    value: JSON.stringify(productosSeleccionados)
                });
                $(this).append(productosInput);
                setTimeout(function() {
                    // Aquí puedes validar la respuesta del servidor o el resultado
                    generarComprobantePDF(); // Llamar la función para generar el comprobante
                }, 500);
            });

            function generarComprobantePDF() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                // Datos de ejemplo, puedes obtenerlos dinámicamente
                var cliente = $('#idCliente option:selected').text();
                var productos = productosSeleccionados;
                var totalPagar = $('#totalPagar').text();

                // Crear el PDF
                doc.setFontSize(18);
                doc.text('Comprobante de Inscripción', 10, 10);

                doc.setFontSize(12);
                doc.text('Cliente: ' + cliente, 10, 20);

                let yPosition = 30;
                productos.forEach(function(producto) {
                    doc.text(`Producto: ${producto.nombre}`, 10, yPosition);
                    doc.text(`Precio: Bs${producto.precio}`, 10, yPosition + 10);
                    doc.text(`Descuento: ${producto.descuento}%`, 10, yPosition + 20);
                    let precioFinal = producto.precio - (producto.precio * (producto.descuento || 0) / 100);
                    doc.text(`Precio Final: Bs${precioFinal.toFixed(2)}`, 10, yPosition + 30);
                    yPosition += 40;
                });

                // Total
                doc.text(`Total Pagado: Bs${totalPagar}`, 10, yPosition + 10);

                // Descargar automáticamente el PDF o abrir en una nueva ventana
                doc.save('comprobante_inscripcion.pdf');
            }
        });
    </script>
@endsection
