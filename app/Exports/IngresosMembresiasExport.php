<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class IngresosMembresiasExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithEvents, WithMapping
{
    protected $ingresosPorMembresias;

    public function __construct($ingresosPorMembresias)
    {
        $this->ingresosPorMembresias = $ingresosPorMembresias;
    }

    /**
     * Mapea cada fila con los datos del reporte
     */
    public function map($ingreso): array
    {
        return [
            $ingreso->clienteNombre . ' ' . $ingreso->clienteApellido,  // Nombre completo del cliente
            $ingreso->membresiaNombre,                                  // Nombre de la membresía
            $ingreso->vendedor,                                         // Vendedor
            number_format($ingreso->totalGanado, 2) . ' BOB',           // Total ganado
        ];
    }

    /**
     * Devuelve los datos que serán exportados
     */
    public function collection()
    {
        return collect($this->ingresosPorMembresias);
    }

    /**
     * Define los encabezados de la tabla
     */
    public function headings(): array
    {
        return [
            'Cliente',
            'Membresía',
            'Vendedor',
            'Total Pagado (BOB)'
        ];
    }

    /**
     * Define la celda desde donde comienzan los datos
     */
    public function startCell(): string
    {
        return 'A6';  // Los datos comienzan desde la celda A6
    }

    /**
     * Aplica estilos básicos a la hoja de cálculo
     */
    public function styles(Worksheet $sheet)
    {
        return [
            6 => ['font' => ['bold' => true]],  // Negrita en los encabezados (fila 6)
        ];
    }

    /**
     * Define eventos personalizados para la hoja de cálculo
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Título del reporte
                $sheet->mergeCells('A1:D1');  // Unir celdas de A1 a D1
                $sheet->setCellValue('A1', 'Reporte de Ingresos por Membresías');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Fecha de generación del reporte
                $sheet->mergeCells('A2:D2');  // Unir celdas de A2 a D2
                $sheet->setCellValue('A2', 'Generado el: ' . Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Aplicar bordes a los encabezados de la tabla
                $sheet->getStyle('A6:D6')->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                    'font' => ['bold' => true],
                ]);

                // Ajustar el tamaño de las columnas automáticamente
                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
