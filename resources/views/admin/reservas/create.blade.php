@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Reservas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Crear</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Columna para el formulario de reserva --}}
        <div class="col-lg-6 col-md-12 mb-4"> {{-- Ajustado para lado a lado en pantallas grandes --}}
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Formulario de Reserva</h5>
                </div>

                <div class="card-body border-bottom-dashed border-bottom">
                    {{-- Mostrar alertas de éxito o error --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>¡Éxito!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Formulario de creación de reserva --}}
                    <form action="{{ route('admin.reservas.store') }}" method="POST">
                        @csrf

                        {{-- Selección del cliente --}}
                        <div class="form-group">
                            <label for="idCliente" class="font-weight-bold">Cliente:</label>
                            <select name="idCliente" id="idCliente" class="form-control select2" required>
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Contenedor de horarios --}}
                        <div id="horarios-container" class="mt-4">
                            <h4 class="font-weight-bold">Horarios</h4>
                            <div class="horario-item form-row mb-3">
                                <div class="card shadow-sm w-100">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="horarios">Horario:</label>
                                                <select name="horarios[]" class="form-control select2" required
                                                    onchange="updateTotal()">
                                                    <option value="">Seleccione un horario</option>
                                                    @foreach ($horarios as $horario)
                                                        <option value="{{ $horario->idHorario }}"
                                                            data-precio="{{ $horario->servicio->precio }}">
                                                            {{ $horario->servicio->nombre }} - {{ $horario->diaSemana }}
                                                            ({{ $horario->horaInicio }} - {{ $horario->horaFin }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" name="cantidad[]" class="form-control" value="1">
                                            <div class="col-auto d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-danger remove-horario">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botón para agregar más horarios --}}
                        <div class="d-flex justify-content-end">
                            <button type="button" id="add-horario" class="btn btn-info mb-3">Agregar Horario</button>
                        </div>

                        {{-- Descuento opcional (porcentaje) --}}
                        <div class="form-group mt-4">
                            <label for="descuento" class="font-weight-bold">Descuento (%):</label>
                            <input type="number" name="descuento" id="descuento" class="form-control" min="0"
                                max="100" step="1" value="0" oninput="updateTotal()">
                        </div>

                        {{-- Estado del pago --}}
                        <div class="form-group">
                            <label for="estadoPago" class="font-weight-bold">Estado del Pago:</label>
                            <select name="estadoPago" id="estadoPago" class="form-control select2">
                                <option value="pendiente">Pendiente</option>
                                <option value="completado">Completado</option>
                            </select>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Crear Reserva</button>
                            <a href="{{ route('admin.reservas.create') }}" class="btn btn-secondary btn-block">Limpiar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Columna para el detalle de la reserva --}}
        <div class="col-lg-6 col-md-12 mb-4"> {{-- Ajustado para lado a lado en pantallas grandes --}}
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="font-weight-bold mb-0">Resumen de la Reserva</h4>
                </div>
                <div class="card-body">
                    <ul id="resumenReserva" class="list-group"></ul>
                    <h5 class="mt-3">Total: <span id="totalReserva">0</span> $</h5>
                    <h5>Descuento: <span id="descuentoReserva">0</span> $</h5>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para clonar y agregar un nuevo horario
            document.getElementById('add-horario').addEventListener('click', function() {
                let horarioContainer = document.querySelector('#horarios-container');
                let horarioItem = document.querySelector('.horario-item').cloneNode(true);

                // Limpiar los valores del nuevo campo horario
                horarioItem.querySelector('select').value = '';
                horarioItem.querySelector('input').value = '1';

                // Agregar el nuevo horario al contenedor
                horarioContainer.appendChild(horarioItem);
            });

            // Eliminar un horario
            document.getElementById('horarios-container').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-horario')) {
                    let horarioItem = event.target.closest('.horario-item');
                    if (document.querySelectorAll('.horario-item').length > 1) {
                        horarioItem.remove();
                        updateTotal();
                    } else {
                        alert('Debe haber al menos un horario.');
                    }
                }
            });
        });

        // Función para actualizar el total de la reserva en tiempo real
        function updateTotal() {
            let total = 0;
            let horarios = document.querySelectorAll('select[name="horarios[]"]');
            let descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;
            let resumenList = document.getElementById('resumenReserva');

            resumenList.innerHTML = '';

            horarios.forEach(horario => {
                let precio = parseFloat(horario.selectedOptions[0].dataset.precio) || 0;
                let subtotal = precio;

                if (subtotal > 0) {
                    total += subtotal;
                    let listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.textContent = `${horario.selectedOptions[0].text} = ${subtotal.toFixed(2)} $`;
                    resumenList.appendChild(listItem);
                }
            });

            let descuento = (total * descuentoPorcentaje) / 100;
            let totalConDescuento = total - descuento;

            document.getElementById('totalReserva').textContent = totalConDescuento.toFixed(2);
            document.getElementById('descuentoReserva').textContent = descuento.toFixed(2);
        }
    </script>
@endpush
