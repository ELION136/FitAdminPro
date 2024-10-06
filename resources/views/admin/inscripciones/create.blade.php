

@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Inscripcion</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Membresias</a></li>
                        <li class="breadcrumb-item active">inscripciones</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body checkout-tab">

                    <form id="membershipForm" action="{{ route('admin.inscripciones.store') }}" method="POST">
                        @csrf
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pills-bill-info" role="tabpanel"
                                aria-labelledby="pills-bill-info-tab">
                                <div>
                                    <h5 class="mb-1">Inscribir a Membresia</h5>
                                    <p class="text-muted mb-4">Por favor complete los campos necesarios</p>
                                </div>

                                <div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cliente_id" class="form-label">Cliente</label>
                                            <select name="cliente_id" id="cliente_id" class="form-select">
                                                <option value="">Seleccione un cliente existente</option>
                                                @foreach ($clientes as $cliente)
                                                    <option value="{{ $cliente->idCliente }}">{{ $cliente->nombre }}
                                                        {{ $cliente->primerApellido }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Seleccione un cliente existente o ingrese
                                                los datos de un
                                                nuevo cliente abajo.</small>
                                        </div>
                                    </div>
                                    <div id="nuevoCliente" class="row {{ old('cliente_id') ? 'd-none' : '' }}">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="form-label">Nombre <span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="nombre" id="nombre" class="form-control"
                                                value="{{ old('nombre') }}">
                                            @error('nombre')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="primerApellido" class="form-label">Primer Apellido <span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="primerApellido" id="primerApellido"
                                                class="form-control" value="{{ old('primerApellido') }}">
                                            @error('primerApellido')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                                            <input type="text" name="segundoApellido" id="segundoApellido"
                                                class="form-control" value="{{ old('segundoApellido') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento <span
                                                    style="color:red">*</span></label>
                                            <input type="date" name="fechaNacimiento" id="fechaNacimiento"
                                                class="form-control" value="{{ old('fechaNacimiento') }}">
                                            @error('fechaNacimiento')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="genero" class="form-label">Género <span
                                                    style="color:red">*</span></label>
                                            <select name="genero" id="genero" class="form-select">
                                                <option value="Masculino"
                                                    {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino
                                                </option>
                                                <option value="Femenino"
                                                    {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino
                                                </option>
                                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro
                                                </option>
                                            </select>
                                            @error('genero')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Correo Electrónico <span
                                                    style="color:red">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nombreUsuario" class="form-label">Nombre de Usuario <span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="nombreUsuario" id="nombreUsuario"
                                                class="form-control" value="{{ old('nombreUsuario') }}">
                                            @error('nombreUsuario')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="membresia_id" class="form-label">Plan de Membresía</label>
                                            <select name="membresia_id" id="membresia_id" class="form-select" required>
                                                <option value="">Seleccione un plan</option>
                                                @foreach ($planes as $plan)
                                                    <option value="{{ $plan->idMembresia }}"
                                                        data-precio="{{ $plan->precio }}">
                                                        {{ $plan->nombre }} - ${{ number_format($plan->precio, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                            <input type="date" name="fecha_inicio" id="fecha_inicio"
                                                class="form-control" required min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="monto_pago" class="form-label">Monto del Pago</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" name="monto_pago" id="monto_pago"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="metodo_pago" class="form-label">Estado del Pago</label>
                                            <select name="estado_pago" id="estado_pago" class="form-select" required>
                                                <option value="completado">Completado</option>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="fallido">fallido</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">Registrar Membresía</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end tab content -->
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col 
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Detalle de Membresia</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body">
                          
                                <div id="clienteDetalles" class="mb-3">
                                    <h5>Detalles del Cliente</h5>
                                    <p><strong>Nombre:</strong> <span id="clienteNombre"></span></p>
                                    <p><strong>Correo Electrónico:</strong> <span id="clienteEmail"></span></p>
                                    <p><strong>Fecha de Nacimiento:</strong> <span id="clienteFechaNacimiento"></span></p>
                                   
                                </div>
        
                                <div id="detalleMembresia">
                                    <h5>Detalles de la Membresía</h5>
                                    <p><strong>Tipo de Membresía:</strong> <span id="tipoMembresia"></span></p>
                                    <p><strong>Fecha de Inicio:</strong> <span id="fechaInicio"></span></p>
                                    <p><strong>Fecha de Fin:</strong> <span id="fechaFin"></span></p>
                                    <p><strong>Monto a Pagar:</strong> $<span id="montoPagar"></span></p>
                                </div>
                        
                    </div>
                </div>
               
            </div>
           
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clienteSelect = document.getElementById('cliente_id');
            const nuevoClienteFields = document.getElementById('nuevoCliente');
            const form = document.getElementById('membershipForm');
            const planSelect = document.getElementById('membresia_id');
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

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach`,
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        @endif




        clienteSelect.addEventListener('change', function() {
            if (this.value === '') {
                nuevoClienteFields.classList.remove('d-none');
                document.getElementById('clienteDetalles').style.display = 'none';
            } else {
                nuevoClienteFields.classList.add('d-none');
                document.getElementById('clienteDetalles').style.display = 'block';

                // Hacer la llamada AJAX para obtener la información del cliente
                const clienteId = this.value;
                fetch(`/admin/inscripciones/cliente/${clienteId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('clienteNombre').innerText =
                            `${data.nombre} ${data.primerApellido}`;
                        document.getElementById('clienteEmail').innerText = data.email;
                        document.getElementById('clienteFechaNacimiento').innerText = data.fechaNacimiento;
                        // Agrega más campos según los datos del cliente
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
        document.getElementById('estado_pago').addEventListener('change', function() {
            const estadoPago = this.value;
            const botonTicket = document.getElementById('generarTicket');

            if (estadoPago === 'completado') {
                botonTicket.disabled = false;
            } else {
                botonTicket.disabled = true;
            }
        });
    </script>

@endpush
