@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Inscribir Membresía</h2>
        </div>
        <div class="card-body">
            <form id="membershipForm" action="{{ route('admin.inscripciones.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="form-select" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->idCliente }}">{{ $cliente->nombre }} {{ $cliente->primerApellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="plan_id" class="form-label">Plan de Membresía</label>
                        <select name="plan_id" id="plan_id" class="form-select" required>
                            <option value="">Seleccione un plan</option>
                            @foreach($planes as $plan)
                                <option value="{{ $plan->idPlan }}" data-precio="{{ $plan->precio }}">
                                    {{ $plan->nombrePlan }} - ${{ number_format($plan->precio, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="monto_pago" class="form-label">Monto del Pago</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Registrar Membresía</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('membershipForm');
        const planSelect = document.getElementById('plan_id');
        const montoInput = document.getElementById('monto_pago');

        planSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');
            montoInput.value = precio ? parseFloat(precio).toFixed(2) : '';
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se registrará la nueva membresía con los datos proporcionados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach`,
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif

    
</script>
@endpush