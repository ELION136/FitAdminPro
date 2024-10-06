<?php

namespace App\Exports;
use App\Models\Cliente;
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

class ClientesExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cliente::with('usuario')
        ->select('nombre', 'primerApellido', 'segundoApellido', 'genero', 'fechaNacimiento', 'eliminado', 'fechaCreacion')
        ->get();
    }


    /**
     * Definir la celda donde comienzan los datos.
     */
    public function startCell(): string
    {
        return 'A6';  // Empieza en A6 para dejar espacio al encabezado personalizado
    }

    /**
     * Definir los encabezados de las columnas.
     */
    public function headings(): array
    {
        return [
            'Nombre',
            'Primer Apellido',
            'Segundo Apellido',
            'Género',
            'Edad',
            'Estado',
            'Fecha de Registro'
        ];
    }

    /**
     * Estilos para las celdas, como negrita para los encabezados.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Negrita en la primera fila de encabezados de la tabla
            6    => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Registrar eventos para personalizar aún más la hoja
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado de la aplicación
                $sheet->mergeCells('A1:G1'); // Unir celdas para el nombre de la aplicación
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

                // Fecha de generación
                $sheet->mergeCells('A2:G2'); // Unir celdas para la fecha de generación
                $sheet->setCellValue('A2', 'Generado el: ' . Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Aplicar bordes a los encabezados de la tabla
                $sheet->getStyle('A6:G6')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDDDDDD'], // Color de fondo gris claro
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Ajustar el tamaño de las columnas
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
            },
        ];
    }
}
