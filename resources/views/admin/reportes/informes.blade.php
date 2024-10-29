@extends('layouts.app')

@section('content')
    <div class="container" id="informe-inscripciones">
        <h1 class="mb-4">Informe de Inscripciones</h1>

        <form action="{{ route('admin.reportes.informes') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success" onclick="exportarPDF()">Exportar a PDF</button>
                    <button type="button" class="btn btn-info" onclick="exportarExcel()">Exportar a Excel</button>
                </div>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Ingreso Total</h5>
                        <p class="card-text display-4">${{ number_format($ingresoTotal, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Membresías Vendidas</h5>
                        <p class="card-text display-4">{{ $conteoMembresias }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Servicios Vendidos</h5>
                        <p class="card-text display-4">{{ $conteoServicios }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Inscripciones</h5>
                        <p class="card-text display-4">{{ $inscripciones->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h3>Top 5 Membresías</h3>
                <ul class="list-group" id="top-membresias">
                    @foreach ($membresiasPopulares as $membresia)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $membresia->membresia->nombre }}
                            <span class="badge bg-primary rounded-pill">{{ $membresia->total }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <h3>Top 5 Servicios</h3>
                <ul class="list-group" id="top-servicios">
                    @foreach ($serviciosPopulares as $servicio)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $servicio->servicio->nombre }}
                            <span class="badge bg-primary rounded-pill">{{ $servicio->total }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <h2>Detalle de Inscripciones</h2>
        <div class="table-responsive">
            <table class="table table-striped" id="tabla-inscripciones">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <!-- En la parte del detalle de inscripciones -->
                <tbody>
                    @foreach ($inscripciones as $inscripcion)
                        <tr>
                            <td>{{ $inscripcion->fechaInscripcion->format('d/m/Y') }}</td>
                            <td>{{ $inscripcion->cliente->nombre }} {{ $inscripcion->cliente->primerApellido }}</td>
                            <td>
                                @foreach ($inscripcion->detallesInscripciones as $detalle)
                                    <!-- Aquí se corrige el nombre de la relación -->
                                    @if ($detalle->tipoProducto == 'membresia')
                                        {{ $detalle->membresia->nombre }}
                                    @else
                                        {{ $detalle->servicio->nombre }}
                                    @endif
                                    <br>
                                @endforeach
                            </td>
                            <td>${{ number_format($inscripcion->totalPago, 2) }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $inscripcion->estado == 'activa' ? 'success' : ($inscripcion->estado == 'vencida' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($inscripcion->estado) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script>
        function exportarPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Título
            doc.setFontSize(18);
            doc.text("Informe de Inscripciones", 14, 20);

            // Resumen
            doc.setFontSize(12);
            doc.text(`Ingreso Total: ${{ number_format($ingresoTotal, 2) }}`, 14, 30);
            doc.text(`Membresías Vendidas: {{ $conteoMembresias }}`, 14, 38);
            doc.text(`Servicios Vendidos: {{ $conteoServicios }}`, 14, 46);
            doc.text(`Total Inscripciones: {{ $inscripciones->count() }}`, 14, 54);

            // Top 5 Membresías
            doc.setFontSize(14);
            doc.text("Top 5 Membresías", 14, 70);
            const membresiaData = [];
            document.querySelectorAll('#top-membresias li').forEach(li => {
                membresiaData.push([li.childNodes[0].textContent.trim(), li.querySelector('.badge').textContent]);
            });
            doc.autoTable({
                startY: 75,
                head: [
                    ['Membresía', 'Cantidad']
                ],
                body: membresiaData,
            });

            // Top 5 Servicios
            doc.text("Top 5 Servicios", 14, doc.lastAutoTable.finalY + 10);
            const servicioData = [];
            document.querySelectorAll('#top-servicios li').forEach(li => {
                servicioData.push([li.childNodes[0].textContent.trim(), li.querySelector('.badge').textContent]);
            });
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 15,
                head: [
                    ['Servicio', 'Cantidad']
                ],
                body: servicioData,
            });

            // Tabla de inscripciones
            doc.addPage();
            doc.text("Detalle de Inscripciones", 14, 20);
            const inscripcionesData = [];
            document.querySelectorAll('#tabla-inscripciones tbody tr').forEach(row => {
                inscripcionesData.push(Array.from(row.cells).map(cell => cell.innerText));
            });
            doc.autoTable({
                startY: 25,
                head: [
                    ['Fecha', 'Cliente', 'Producto', 'Monto', 'Estado']
                ],
                body: inscripcionesData,
            });

            doc.save("informe_inscripciones.pdf");
        }

        function exportarExcel() {
            const wb = XLSX.utils.book_new();

            // Resumen
            const resumenData = [
                ["Ingreso Total", "Membresías Vendidas", "Servicios Vendidos", "Total Inscripciones"],
                ["${{ number_format($ingresoTotal, 2) }}", "{{ $conteoMembresias }}", "{{ $conteoServicios }}",
                    "{{ $inscripciones->count() }}"
                ]
            ];
            const resumenWS = XLSX.utils.aoa_to_sheet(resumenData);
            XLSX.utils.book_append_sheet(wb, resumenWS, "Resumen");

            // Top 5 Membresías
            const membresiaData = [
                ["Membresía", "Cantidad"]
            ];
            document.querySelectorAll('#top-membresias li').forEach(li => {
                membresiaData.push([li.childNodes[0].textContent.trim(), li.querySelector('.badge').textContent]);
            });
            const membresiaWS = XLSX.utils.aoa_to_sheet(membresiaData);
            XLSX.utils.book_append_sheet(wb, membresiaWS, "Top 5 Membresías");

            // Top 5 Servicios
            const servicioData = [
                ["Servicio", "Cantidad"]
            ];
            document.querySelectorAll('#top-servicios li').forEach(li => {
                servicioData.push([li.childNodes[0].textContent.trim(), li.querySelector('.badge').textContent]);
            });
            const servicioWS = XLSX.utils.aoa_to_sheet(servicioData);
            XLSX.utils.book_append_sheet(wb, servicioWS, "Top 5 Servicios");

            // Detalle de Inscripciones
            const inscripcionesData = [
                ["Fecha", "Cliente", "Producto", "Monto", "Estado"]
            ];
            document.querySelectorAll('#tabla-inscripciones tbody tr').forEach(row => {
                inscripcionesData.push(Array.from(row.cells).map(cell => cell.innerText));
            });
            const inscripcionesWS = XLSX.utils.aoa_to_sheet(inscripcionesData);
            XLSX.utils.book_append_sheet(wb, inscripcionesWS, "Detalle Inscripciones");

            XLSX.writeFile(wb, "informe_inscripciones.xlsx");
        }
    </script>
@endpush
