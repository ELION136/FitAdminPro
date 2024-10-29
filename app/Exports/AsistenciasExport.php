<?php

namespace App\Exports;

use App\Models\Asistencia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class AsistenciasExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    protected $asistencias;

    public function __construct($asistencias)
    {
        $this->asistencias = $asistencias;
    }

    public function collection()
    {
        return $this->asistencias->map(function ($asistencia) {
            return [
                $asistencia->fechaAsistencia,
                $asistencia->clienteNombre,
                $asistencia->metodoRegistro,
                $asistencia->estado,
            ];
        });
    }

    public function startCell(): string
    {
        return 'A6';  // Los datos comienzan desde la celda A6
    }

    public function headings(): array
    {
        return [
            'Fecha de Asistencia',
            'Cliente',
            'Método de Registro',
            'Estado'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => ['font' => ['bold' => true]],  // Negrita en la fila de encabezados (fila 6)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado de la aplicación
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Nombre de la Aplicación - Sistema de Gym');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Fecha de generación del reporte
                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Generado el: ' . Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Aplicar bordes y estilos
                $sheet->getStyle('A6:D6')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDDDDDD'],
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}

