<?php

namespace App\Exports;

use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class InscripcionesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Retorna la colección de inscripciones.
     */
    public function collection()
    {
        return Inscripcion::with('cliente', 'membresia')->get();
    }

    /**
     * Define las cabeceras para el archivo Excel.
     */
    public function headings(): array
    {
        return [
            '#',
            'Cliente',
            'Membresía',
            'Fecha de Inicio',
            'Fecha de Fin',
            'Estado',
            'Monto de Pago (Bs.)',
            'Estado de Pago',
            'Fecha de Registro'
        ];
    }

    /**
     * Mapea los datos de cada inscripción para mostrarlos en cada fila de Excel.
     */
    public function map($inscripcion): array
    {
        return [
            $inscripcion->id,
            $inscripcion->cliente->nombre . ' ' . $inscripcion->cliente->primerApellido . ' ' . $inscripcion->cliente->segundoApellido,
            $inscripcion->membresia->nombre,
            Carbon::parse($inscripcion->fechaInicio)->format('d/m/Y'),
            Carbon::parse($inscripcion->fechaFin)->format('d/m/Y'),
            ucfirst($inscripcion->estado),
            number_format($inscripcion->montoPago, 2, ',', '.') . ' Bs.',
            ucfirst($inscripcion->estadoPago),
            Carbon::parse($inscripcion->created_at)->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * Aplica estilos personalizados a la hoja de cálculo.
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para las cabeceras
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '007bff'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Alternar color de fondo en filas
        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'f2f2f2'],
                    ],
                ]);
            }
        }
    }
}
