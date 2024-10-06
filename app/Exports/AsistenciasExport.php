<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AsistenciasExport implements FromView
{
    protected $asistencias;

    public function __construct($asistencias)
    {
        $this->asistencias = $asistencias;
    }

    public function view(): View
    {
        return view('admin.asistencias.reporte_excel', [
            'asistencias' => $this->asistencias
        ]);
    }
}
