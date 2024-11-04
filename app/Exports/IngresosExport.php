<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class IngresosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, 
    ShouldAutoSize, WithColumnFormatting, WithProperties, WithCustomStartCell
{
    protected $ingresos;
    protected $fechaInicio;
    protected $fechaFin;
    protected $totalIngresos;

    public function __construct(Collection $ingresos, $fechaInicio, $fechaFin)
    {
        $this->ingresos = $ingresos;
        $this->fechaInicio = Carbon::parse($fechaInicio);
        $this->fechaFin = Carbon::parse($fechaFin);
        $this->totalIngresos = $ingresos->sum('subtotal');
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function properties(): array
    {
        return [
            'creator'        => auth()->user()->nombreUsuario,
            'title'         => 'Reporte de Ingresos - Gimnasio Urbano',
            'description'   => 'Reporte de ingresos del ' . $this->fechaInicio->format('d/m/Y') . 
                             ' al ' . $this->fechaFin->format('d/m/Y'),
            'company'       => 'Gimnasio Urbano',
        ];
    }

    public function collection()
    {
        return $this->ingresos;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Cliente',
            'Producto',
            'Precio Base',
            'Descuento',
            'Subtotal',
            'Vendedor',
            'Método Pago',
            '% Descuento',
            'Estado',
        ];
    }

    public function map($ingreso): array
    {
        $descuentoPorcentaje = $ingreso->precio > 0 
            ? round(($ingreso->descuento / $ingreso->precio) * 100, 2) 
            : 0;

        return [
            Carbon::parse($ingreso->fechaInscripcion)->format('d/m/Y H:i'),
            $ingreso->clienteNombre,
            $ingreso->tipoProducto,
            $ingreso->precio,
            $ingreso->descuento,
            $ingreso->subtotal,
            $ingreso->vendedor,
            $ingreso->metodoPago ?? 'N/A',
            $descuentoPorcentaje,
            $ingreso->estado ?? 'Completado',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'I' => NumberFormat::FORMAT_PERCENTAGE_00,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'GIMNASIO URBANO - REPORTE DE INGRESOS');
        
        // Subtítulo con rango de fechas
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Periodo: ' . $this->fechaInicio->format('d/m/Y') . 
            ' al ' . $this->fechaFin->format('d/m/Y'));

        // Total de ingresos
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Total de Ingresos: $' . number_format($this->totalIngresos, 2));

        // Aplicar bordes a toda la tabla
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A4:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return [
            // Estilo del título
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Estilo del subtítulo
            2 => [
                'font' => ['italic' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Estilo del total
            3 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ],
            // Estilo de los encabezados
            4 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Estilo para todas las filas de datos
            'A5:J'.$lastRow => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}