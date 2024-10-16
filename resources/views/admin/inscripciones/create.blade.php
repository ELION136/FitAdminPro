@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Nueva Inscripción</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.inscripciones.store') }}" method="POST" id="inscripcionForm">
        @csrf
        <div class="mb-3">
            <label for="idCliente" class="form-label">Cliente</label>
            <select name="idCliente" id="idCliente" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->idCliente }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tipoProducto" class="form-label">Tipo de Producto</label>
            <select name="tipoProducto" id="tipoProducto" class="form-select" required>
                <option value="">Seleccione el tipo de producto</option>
                <option value="membresia">Membresía</option>
                <option value="servicio">Servicio</option>
            </select>
        </div>

        <div id="membresiaOptions" style="display: none;">
            <div class="mb-3">
                <label for="idMembresia" class="form-label">Membresía</label>
                <select name="idMembresia" id="idMembresia" class="form-select">
                    <option value="">Seleccione una membresía</option>
                    @foreach($membresias as $membresia)
                        <option value="{{ $membresia->idMembresia }}" data-precio="{{ $membresia->precio }}">
                            {{ $membresia->nombre }} - ${{ number_format($membresia->precio, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="servicioOptions" style="display: none;">
            <div class="mb-3">
                <label for="idSeccion" class="form-label">Sección</label>
                <select name="idSeccion" id="idSeccion" class="form-select">
                    <option value="">Seleccione una sección</option>
                    @foreach($secciones as $seccion)
                        <option value="{{ $seccion->idSeccion }}" data-precio="{{ $seccion->precioPorSeccion }}" data-capacidad="{{ $seccion->capacidad }}">
                            {{ $seccion->nombre }} - ${{ number_format($seccion->precioPorSeccion, 2) }} por sección
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidadSecciones" class="form-label">Cantidad de Secciones</label>
                <input type="number" name="cantidadSecciones" id="cantidadSecciones" class="form-control" min="1" value="1">
            </div>
        </div>

        <div class="mb-3">
            <label for="totalPago" class="form-label">Total a Pagar</label>
            <input type="number" name="totalPago" id="totalPago" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Crear Inscripción</button>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoProductoSelect = document.getElementById('tipoProducto');
    const membresiaOptions = document.getElementById('membresiaOptions');
    const servicioOptions = document.getElementById('servicioOptions');
    const idMembresiaSelect = document.getElementById('idMembresia');
    const idSeccionSelect = document.getElementById('idSeccion');
    const cantidadSeccionesInput = document.getElementById('cantidadSecciones');
    const totalPagoInput = document.getElementById('totalPago');

    tipoProductoSelect.addEventListener('change', function() {
        if (this.value === 'membresia') {
            membresiaOptions.style.display = 'block';
            servicioOptions.style.display = 'none';
        } else if (this.value === 'servicio') {
            membresiaOptions.style.display = 'none';
            servicioOptions.style.display = 'block';
        } else {
            membresiaOptions.style.display = 'none';
            servicioOptions.style.display = 'none';
        }
        calcularTotal();
    });

    idMembresiaSelect.addEventListener('change', calcularTotal);
    idSeccionSelect.addEventListener('change', calcularTotal);
    cantidadSeccionesInput.addEventListener('input', calcularTotal);

    function calcularTotal() {
        let total = 0;
        if (tipoProductoSelect.value === 'membresia' && idMembresiaSelect.value) {
            const selectedOption = idMembresiaSelect.options[idMembresiaSelect.selectedIndex];
            total = parseFloat(selectedOption.dataset.precio);
        } else if (tipoProductoSelect.value === 'servicio' && idSeccionSelect.value) {
            const selectedOption = idSeccionSelect.options[idSeccionSelect.selectedIndex];
            const precioPorSeccion = parseFloat(selectedOption.dataset.precio);
            const cantidadSecciones = parseInt(cantidadSeccionesInput.value) || 0;
            total = precioPorSeccion * cantidadSecciones;
        }
        totalPagoInput.value = total.toFixed(2);
    }

    document.getElementById('inscripcionForm').addEventListener('submit', function(e) {
        const tipoProducto = tipoProductoSelect.value;
        const idSeccion = idSeccionSelect.value;
        const cantidadSecciones = parseInt(cantidadSeccionesInput.value) || 0;

        if (tipoProducto === 'servicio' && idSeccion) {
            const selectedOption = idSeccionSelect.options[idSeccionSelect.selectedIndex];
            const capacidad = parseInt(selectedOption.dataset.capacidad);

            if (cantidadSecciones > capacidad) {
                e.preventDefault();
                alert(`La sección seleccionada solo tiene capacidad para ${capacidad} secciones.`);
            }
        }
    });
});
</script>
@endpush