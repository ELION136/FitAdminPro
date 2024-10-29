<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IngresosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ingresos;

    public function __construct(Collection $ingresos)
    {
        $this->ingresos = $ingresos;
    }

    /**
     * Devuelve la colección de datos que se exportará.
     */
    public function collection()
    {
        return $this->ingresos;
    }

    /**
     * Define los encabezados de las columnas del archivo Excel.
     */
    public function headings(): array
    {
        return [
            'Fecha de Inscripción',
            'Cliente',
            'Tipo de Producto',
            'Precio',
            'Descuento',
            'Cantidad',
            'Subtotal',
            'Vendedor'
        ];
    }

    /**
     * Define cómo se debe mapear cada fila del archivo Excel.
     */
    public function map($ingreso): array
    {
        return [
            $ingreso->fechaInscripcion,
            $ingreso->clienteNombre,
            $ingreso->tipoProducto,
            number_format($ingreso->precio, 2),
            number_format($ingreso->descuento, 2),
            $ingreso->cantidad,
            number_format($ingreso->subtotal, 2),
            $ingreso->vendedor
        ];
    }
}
