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



    protected $inscripciones;

    public function __construct($inscripciones)
    {
        $this->inscripciones = $inscripciones;
    }

    public function collection()
    {
        return $this->inscripciones->map(function ($inscripcion) {
            $detalleProducto = (isset($inscripcion->detalleInscripciones[0]))
                ? $inscripcion->detalleInscripciones[0]->tipoProducto
                : 'No definido';

            return [
                $inscripcion->cliente->nombre . ' ' . $inscripcion->cliente->primerApellido,
                $detalleProducto,
                $inscripcion->estado,
                $inscripcion->fechaInscripcion,
                $inscripcion->totalPago
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Tipo de Producto',
            'Estado',
            'Fecha de Inscripción',
            'Total Pagado'
        ];
    }

    /**
     * Mapea los datos de cada inscripción para mostrarlos en cada fila de Excel.
     */
    public function map($inscripcion): array
    {
        // Validar que la membresía o sección existan antes de acceder a sus datos
        $detalleProducto = isset($inscripcion->detalleInscripciones[0]) ? $inscripcion->detalleInscripciones[0]->tipoProducto : 'No definido';

        return [
            $inscripcion->id,
            $inscripcion->cliente->nombre . ' ' . $inscripcion->cliente->primerApellido . ' ' . ($inscripcion->cliente->segundoApellido ?? ''),
            $detalleProducto, // Puede ser membresía o servicio
            Carbon::parse($inscripcion->fechaInscripcion)->format('d/m/Y'),
            ucfirst($inscripcion->estado), // Activa, vencida, cancelada
            number_format($inscripcion->totalPago, 2, ',', '.') . ' Bs.', // Monto pagado
            Carbon::parse($inscripcion->fechaCreacion)->format('d/m/Y H:i:s'), // Fecha de creación
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
