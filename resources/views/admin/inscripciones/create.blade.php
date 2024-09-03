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
                        <select name="cliente_id" id="cliente_id" class="form-select">
                            <option value="">Seleccione un cliente existente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->idCliente }}">{{ $cliente->nombre }} {{ $cliente->primerApellido }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Seleccione un cliente existente o ingrese los datos de un nuevo cliente abajo.</small>
                    </div>
                </div>

                <div id="nuevoCliente" class="row {{ old('cliente_id') ? 'd-none' : '' }}">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre <span style="color:red">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="primerApellido" class="form-label">Primer Apellido <span style="color:red">*</span></label>
                        <input type="text" name="primerApellido" id="primerApellido" class="form-control" value="{{ old('primerApellido') }}">
                        @error('primerApellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundoApellido" id="segundoApellido" class="form-control" value="{{ old('segundoApellido') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento <span style="color:red">*</span></label>
                        <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" value="{{ old('fechaNacimiento') }}">
                        @error('fechaNacimiento')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="genero" class="form-label">Género <span style="color:red">*</span></label>
                        <select name="genero" id="genero" class="form-select">
                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('genero')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Correo Electrónico <span style="color:red">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nombreUsuario" class="form-label">Nombre de Usuario <span style="color:red">*</span></label>
                        <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" value="{{ old('nombreUsuario') }}">
                        @error('nombreUsuario')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <hr>

                <div class="row">
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

                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="monto_pago" class="form-label">Monto del Pago</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="monto_pago" id="monto_pago" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago</label>
                        <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
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
        const clienteSelect = document.getElementById('cliente_id');
        const nuevoClienteFields = document.getElementById('nuevoCliente');
        const form = document.getElementById('membershipForm');
        const planSelect = document.getElementById('plan_id');
        const montoInput = document.getElementById('monto_pago');

        // Inicializar visibilidad del formulario de nuevo cliente
        if (clienteSelect.value === '') {
            nuevoClienteFields.classList.remove('d-none');
        } else {
            nuevoClienteFields.classList.add('d-none');
        }

        clienteSelect.addEventListener('change', function() {
            if (this.value === '') {
                nuevoClienteFields.classList.remove('d-none');
            } else {
                nuevoClienteFields.classList.add('d-none');
            }
        });

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
