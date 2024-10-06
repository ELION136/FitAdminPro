@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4 mb-4">Crear Nueva Reserva</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Formulario de Reserva</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="idCliente" class="form-label">Cliente</label>
                        <select class="form-select @error('idCliente') is-invalid @enderror" id="idCliente" name="idCliente" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->idCliente }}" {{ old('idCliente') == $cliente->idCliente ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->primerApellido }}
                                </option>
                            @endforeach
                        </select>
                        @error('idCliente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="servicios">
                    <h4 class="mt-4 mb-3">Servicios</h4>
                    <div class="servicio-item mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Servicio</label>
                                <select class="form-select servicio-select" name="servicios[]" required>
                                    <option value="">Seleccione un servicio</option>
                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->idServicio }}" data-precio="{{ $servicio->precio }}">
                                            {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control fecha-servicio" name="fechas[]" required>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Horario</label>
                                <select class="form-select horario-select" name="horarios[]" required>
                                    <option value="">Seleccione un horario</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Cantidad</label>
                                <input type="number" class="form-control cantidad-servicio" name="cantidades[]" value="1" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="agregarServicio" class="btn btn-secondary mt-2">
                    <i class="fas fa-plus"></i> Agregar Servicio
                </button>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h4>Total: $<span id="totalReserva">0.00</span></h4>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Crear Reserva</button>
                    <a href="{{ route('reservas.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviciosContainer = document.getElementById('servicios');
    const agregarServicioBtn = document.getElementById('agregarServicio');
    const totalReservaSpan = document.getElementById('totalReserva');

    agregarServicioBtn.addEventListener('click', function() {
        const servicioItem = document.querySelector('.servicio-item').cloneNode(true);
        servicioItem.querySelectorAll('input, select').forEach(input => {
            input.value = '';
        });
        serviciosContainer.appendChild(servicioItem);
        actualizarEventListeners();
    });

    function actualizarEventListeners() {
        document.querySelectorAll('.servicio-select').forEach(select => {
            select.addEventListener('change', cargarHorarios);
        });

        document.querySelectorAll('.fecha-servicio').forEach(input => {
            input.addEventListener('change', cargarHorarios);
        });

        document.querySelectorAll('.cantidad-servicio, .servicio-select').forEach(input => {
            input.addEventListener('change', calcularTotal);
        });
    }

    function cargarHorarios(event) {
        const servicioSelect = event.target.closest('.servicio-item').querySelector('.servicio-select');
        const fechaInput = event.target.closest('.servicio-item').querySelector('.fecha-servicio');
        const horarioSelect = event.target.closest('.servicio-item').querySelector('.horario-select');

        if (servicioSelect.value && fechaInput.value) {
            // Aquí deberías hacer una llamada AJAX para obtener los horarios disponibles
            // Por ahora, simularemos algunos horarios
            horarioSelect.innerHTML = `
                <option value="">Seleccione un horario</option>
                <option value="1">09:00 AM</option>
                <option value="2">11:00 AM</option>
                <option value="3">02:00 PM</option>
            `;
        }
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.servicio-item').forEach(item => {
            const servicioSelect = item.querySelector('.servicio-select');
            const cantidadInput = item.querySelector('.cantidad-servicio');
            if (servicioSelect.value && cantidadInput.value) {
                const precio = parseFloat(servicioSelect.options[servicioSelect.selectedIndex].dataset.precio);
                const cantidad = parseInt(cantidadInput.value);
                total += precio * cantidad;
            }
        });
        totalReservaSpan.textContent = total.toFixed(2);
    }

    actualizarEventListeners();
});
</script>
@endpush