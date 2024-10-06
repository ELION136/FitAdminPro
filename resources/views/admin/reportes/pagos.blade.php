@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Reporte de Pagos</h1>
    
    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select id="estadoPago" class="form-control">
                <option value="">Estado de Pago</option>
                <option value="pendiente">Pendiente</option>
                <option value="completado">Completado</option>
                <option value="fallido">Fallido</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" id="fechaInicio" class="form-control" placeholder="Fecha Inicio">
        </div>
        <div class="col-md-3">
            <input type="date" id="fechaFin" class="form-control" placeholder="Fecha Fin">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" id="buscarPagos">Buscar</button>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered" id="pagosTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reserva</th>
                <th>Monto</th>
                <th>Fecha de Pago</th>
                <th>Estado de Pago</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using AJAX -->
        </tbody>
    </table>

    <!-- Export Buttons -->
    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-success" id="exportPdf">Exportar PDF</button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-success" id="exportExcel">Exportar Excel</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Fetch payments based on filters
        $('#buscarPagos').click(function() {
            $.ajax({
                url: '{{ route('pagos.get') }}',
                method: 'GET',
                data: {
                    estadoPago: $('#estadoPago').val(),
                    fechaInicio: $('#fechaInicio').val(),
                    fechaFin: $('#fechaFin').val()
                },
                success: function(data) {
                    let rows = '';
                    data.forEach(function(pago) {
                        rows += `
                            <tr>
                                <td>${pago.idPago}</td>
                                <td>${pago.idReserva}</td>
                                <td>${pago.monto}</td>
                                <td>${pago.fechaPago}</td>
                                <td>${pago.estadoPago}</td>
                            </tr>
                        `;
                    });
                    $('#pagosTable tbody').html(rows);
                }
            });
        });

        // Export to PDF
        $('#exportPdf').click(function() {
            window.location.href = '{{ route('pagos.exportPDF') }}';
        });

        // Export to Excel
        $('#exportExcel').click(function() {
            window.location.href = '{{ route('pagos.exportExcel') }}';
        });
    });
</script>
@endsection
