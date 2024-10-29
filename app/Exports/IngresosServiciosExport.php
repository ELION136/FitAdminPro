<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IngresosServiciosExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithEvents, WithMapping
{
    protected $ingresosPorServicios;

    public function __construct($ingresosPorServicios)
    {
        $this->ingresosPorServicios = $ingresosPorServicios;
    }

    // Devuelve la colección de datos
    public function collection()
    {
        return $this->ingresosPorServicios;
    }

    // Mapeo de los datos para personalizar cómo se mostrarán en cada fila
    public function map($ingreso): array
    {
        return [
            $ingreso->clienteNombre . ' ' . $ingreso->clienteApellido,
            $ingreso->servicioNombre,
            $ingreso->vendedor,
            number_format($ingreso->totalGanado, 2) . ' BOB',
        ];
    }

    // Encabezados de las columnas
    public function headings(): array
    {
        return [
            'Cliente',
            'Servicio',
            'Vendedor',
            'Total Pagado (BOB)'
        ];
    }

    // Define en qué celda comenzar
    public function startCell(): string
    {
        return 'A6'; // Comienza en la celda A6
    }

    // Define los estilos aplicados a la hoja de cálculo
    public function styles(Worksheet $sheet)
    {
        return [
            6 => ['font' => ['bold' => true]], // Negrita en la fila de encabezados (fila 6)
        ];
    }

    // Registrar eventos para personalizar la hoja
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado general
                $sheet->mergeCells('A1:D1'); // Unir celdas de A1 a D1
                $sheet->setCellValue('A1', 'Nombre de la Empresa - Reporte de Ingresos por Servicios');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Fecha de generación del reporte
                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Generado el: ' . \Carbon\Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Aplicar estilos a los encabezados
                $sheet->getStyle('A6:D6')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDDDDDD'],
                    ],
                ]);

                // Ajustar el ancho de las columnas automáticamente
                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
