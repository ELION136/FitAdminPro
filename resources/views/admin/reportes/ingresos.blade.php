@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-money-dollar-box-line me-2"></i> Reporte de Ingresos</h1>
    </div>
    <div class="card-body">

        <!-- Formulario de Filtros -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.reportes.ingresos') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaInicio" class="form-label">Fecha desde</label>
                            <input type="date" name="fechaInicio" class="form-control" value="{{ request('fechaInicio') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaFin" class="form-label">Fecha hasta</label>
                            <input type="date" name="fechaFin" class="form-control" value="{{ old('fechaFin', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                        </div>                                          
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.ingresos') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
            <a href="{{ route('admin.reportes.ingresos-excel', request()->query()) }}" class="btn btn-success"><i class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
        </div>

        <!-- Tabla de ingresos -->
        <div class="table-responsive">
            @if($ingresos->isEmpty())
                <div class="alert alert-warning text-center">
                    <i class="ri-alert-line me-2"></i> No se encontraron ingresos para los filtros seleccionados.
                </div>
            @else
                <table class="table table-hover table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><i class="ri-calendar-line me-1"></i> Fecha de Inscripción</th>
                            <th><i class="ri-user-line me-1"></i> Cliente</th>
                            <th><i class="ri-shopping-bag-line me-1"></i> Tipo de Producto</th>
                            <th><i class="ri-price-tag-3-line me-1"></i> Precio</th>
                            <th><i class="ri-discount-line me-1"></i> Descuento</th>
                            <th><i class="ri-currency-line me-1"></i> Subtotal</th>
                            <th><i class="ri-user-3-line me-1"></i> Vendedor</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($ingresos as $ingreso)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($ingreso->fechaInscripcion)->format('d/m/Y') }}</td>
                                <td>{{ $ingreso->clienteNombre }}</td>
                                <td>{{ $ingreso->tipoProducto }}</td>
                                <td>{{ number_format($ingreso->precio, 2) }}</td>
                                <td>{{ number_format($ingreso->descuento, 2) }}</td>
                                <td>{{ number_format($ingreso->subtotal, 2) }}</td>
                                <td>{{ $ingreso->vendedor }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Total de ingresos -->
        <div class="mt-4">
            <h4><i class="ri-money-dollar-box-line me-2"></i>Total de Ingresos: {{ number_format($totalIngresos, 2) }} BOB</h4>
        </div>

    </div>
</div>

@endsection

@push('scripts')

<script>
    function abrirVentanaPDF() {
        const url = new URL('{{ route('admin.reportes.ingresos-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search);
        url.search = params;
        window.open(url, '_blank', 'width=800,height=600');
    }
</script>

@endpush
