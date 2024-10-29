<?php

namespace App\Exports;

use App\Models\Entrenador;
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
class EntrenadoresExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $entrenadores;

    public function __construct($entrenadores)
    {
        $this->entrenadores = $entrenadores;
    }

    public function collection()
    {
        // Usar los entrenadores pasados a través del constructor
        return $this->entrenadores;
    }

    public function headings(): array
    {
        return [
            'Nombre', 'Primer Apellido', 'Segundo Apellido', 'Especialidad', 'Género', 'Teléfono', 'Fecha de Nacimiento', 'Fecha de Contratación'
        ];
    }

    public function startCell(): string
    {
        return 'A6'; // Empieza en A6 para dejar espacio para el encabezado personalizado
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6    => ['font' => ['bold' => true]], // Encabezado en negrita
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado
                $sheet->mergeCells('A1:H1'); // Unir celdas para el nombre del sistema
                $sheet->setCellValue('A1', 'Sistema de Gym - Reporte de Entrenadores');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Fecha de generación
                $sheet->mergeCells('A2:H2');
                $sheet->setCellValue('A2', 'Generado el: ' . now()->format('d/m/Y H:i:s'));
                $sheet->getStyle('A2')->applyFromArray(['alignment' => ['horizontal' => 'center']]);

                // Estilos de los encabezados
                $sheet->getStyle('A6:H6')->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => 'thin']],
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
