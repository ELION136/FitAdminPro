@push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({ // Se aplica a todas las tablas que tengan la clase 'datatable'
                dom: 'Bfrtip',
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 75, 100],
                pageLength: 5,
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-primary'
                    },

                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success',
                        title: 'FitAdminPro',
                        filename: 'Registro',
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Cambiar el texto en una celda específica (A1 en este caso)
                            $('row c[r^="A1"] t', sheet).text('FitAdminPro');

                            // Añadir color de fondo a la primera fila
                            $('row:first c', sheet).attr('s', '42');

                            // Ajustar el ancho de las columnas
                            $('col', sheet).attr('width', 20);

                            // Añadir una imagen al Excel (requiere la URL en base64)
                            // Esto es más complejo y requiere trabajar con la estructura XML del archivo
                            // y herramientas adicionales para insertar imágenes en Excel.
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger',
                        title: 'FitAdminPro',
                        filename: 'Registro',
                        orientation: 'landscape', // Orientación de la página: 'portrait' o 'landscape'
                        pageSize: 'A4', // Tamaño del papel: 'A4', 'A3', 'letter', etc.
                        customize: function(doc) {
                            doc.content[1].margin = [100, 0, 100,
                                0
                            ]; // Ajustar márgenes [left, top, right, bottom]
                            doc.content[1].alignment = 'center'; // Centrar el contenido
                            doc.styles.tableHeader.alignment =
                                'center'; // Centrar el encabezado de la tabla
                            doc.styles.tableHeader.fillColor =
                                '#D3D3D3'; // Color de fondo del encabezado
                            doc.styles.tableHeader.color =
                                '#000000'; // Color del texto del encabezado
                            doc.content.splice(1, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: 'center',
                                text: 'Este es un PDF personalizado',
                                fontSize: 14,
                                bold: true,
                                color: '#000000'
                            });
                            // Añadir una imagen al PDF (requiere la URL en base64)

                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-info'
                    }
                ],
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    decimal: "",
                    emptyTable: "No hay datos disponibles en la tabla",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                    infoFiltered: "(filtrado de _MAX_ entradas totales)",
                    thousands: ",",
                    lengthMenu: "Mostrar _MENU_ entradas",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "No se encontraron registros coincidentes",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    aria: {
                        sortAscending: ": activar para ordenar la columna de manera ascendente",
                        sortDescending: ": activar para ordenar la columna de manera descendente"
                    }
                },
            });
        });
    </script>
@endpush
