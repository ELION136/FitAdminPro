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

    public function __construct($clientes)
    {
        $this->clientes = $clientes;
    }
    public function collection()
    {

        
        // Obtener los datos de los clientes, calculando la edad y el estado
        return Cliente::select('nombre', 'primerApellido', 'segundoApellido', 'genero', 'fechaNacimiento', 'eliminado', 'fechaCreacion')
            ->get()
            ->map(function ($cliente) {
                $estado = $cliente->eliminado ? 'Inactivo' : 'Activo';
                $edad = Carbon::parse($cliente->fechaNacimiento)->age;
                return [
                    $cliente->nombre,
                    $cliente->primerApellido,
                    $cliente->segundoApellido,
                    $cliente->genero,
                    $edad,  // Calcular edad a partir de la fecha de nacimiento
                    $estado,  // Estado basado en el campo 'eliminado'
                    Carbon::parse($cliente->fechaCreacion)->format('d/m/Y'),  // Formatear la fecha de registro
                ];
            });
    }


    /**
     * Definir la celda donde comienzan los datos.
     */
    public function startCell(): string
    {
        return 'A6';  // Los datos comienzan desde la celda A6
    }

    /**
     * Definir los encabezados de las columnas.
     *
     * @return array
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
     * Aplicar estilos básicos a las celdas.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            6 => ['font' => ['bold' => true]],  // Negrita en la fila de encabezados (fila 6)
        ];
    }

    /**
     * Registrar eventos para personalizar la hoja de Excel.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado de la aplicación
                $sheet->mergeCells('A1:G1');  // Unir celdas de A1 a G1
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
                $sheet->mergeCells('A2:G2');  // Unir celdas de A2 a G2
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
                        'startColor' => ['argb' => 'FFDDDDDD'],  // Fondo gris claro para los encabezados
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Ajustar el tamaño de las columnas automáticamente
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
