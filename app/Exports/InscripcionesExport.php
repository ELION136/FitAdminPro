<?php

namespace App\Exports;

use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InscripcionesExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithEvents
{
    protected $inscripciones;

    public function __construct(Collection $inscripciones)
    {
        $this->inscripciones = $inscripciones;
    }

    public function collection()
    {
        return $this->inscripciones->map(function ($inscripcion) {
            return [
                $inscripcion->fechaInscripcion->format('d/m/Y'),
                $inscripcion->clienteNombre,
                $inscripcion->tipoProducto,
                number_format($inscripcion->precio, 2),
                number_format($inscripcion->descuento, 2),
                number_format($inscripcion->subtotal, 2),
                $inscripcion->vendedor,
            ];
        });
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'Fecha de Inscripción',
            'Nombre del Cliente',
            'Producto',
            'Precio',
            'Descuento',
            'Subtotal',
            'Vendedor',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado del reporte
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'Reporte de Inscripciones');
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
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A2', 'Generado el: ' . Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Aplicar bordes a los encabezados de la tabla
                $sheet->getStyle('A6:G6')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDDDDDD'],
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Ajustar el tamaño de las columnas
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
