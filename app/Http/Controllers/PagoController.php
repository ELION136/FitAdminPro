<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        // Inicializamos la consulta con las relaciones necesarias
        $query = Pago::query()->with(['reserva.cliente']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estadoPago', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fechaPago', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fechaPago', '<=', $request->fecha_hasta);
        }

        if ($request->filled('monto_minimo')) {
            $query->where('monto', '>=', $request->monto_minimo);
        }

        if ($request->filled('monto_maximo')) {
            $query->where('monto', '<=', $request->monto_maximo);
        }

        // Excluir pagos eliminados (eliminado = 0)
        $query->where('eliminado', 1);

        // Paginación
        $pagos = $query->orderBy('fechaPago', 'desc')->paginate(15);

        // Estadísticas
        $estadisticas = [
            'total_pagos' => $pagos->where('eliminado', 1)->sum('monto'),
            'pagos_completados' => $query->where('estadoPago', 'completado')->count(),
            'pagos_pendientes' => $query->where('estadoPago', 'pendiente')->count(),
            'pagos_fallidos' => $query->where('estadoPago', 'fallido')->count(),
        ];


        return view('admin.pagos.index', compact('pagos', 'estadisticas'));
    }
    public function updateEstado(Request $request, $idPago)
    {
        // Validar el valor del estado
        $request->validate([
            'estadoPago' => 'required|in:pendiente,completado,fallido',
        ]);

        // Buscar el pago por ID
        $pago = Pago::find($idPago);

        // Verificar si el pago existe
        if (!$pago) {
            return redirect()->route('admin.pagos.index')->with('error', 'El pago no fue encontrado.');
        }

        // Actualizar el estado del pago
        $pago->estadoPago = $request->estadoPago;
        $pago->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.pagos.index')->with('success', 'Estado del pago actualizado correctamente.');
    }
    public function cancelar($idPago)
    {
        // Buscar el pago por ID
        $pago = Pago::find($idPago);

        // Verificar si el pago existe
        if (!$pago) {
            return redirect()->route('admin.pagos.index')->with('error', 'El pago no fue encontrado.');
        }

        // Marcar el pago como cancelado (eliminado = 0)
        $pago->eliminado = 0;
        $pago->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.pagos.index')->with('success', 'Pago cancelado correctamente.');
    }



}
