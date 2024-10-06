<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Horario;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleReserva;
use App\Models\Servicio;
use App\Models\Pago;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
class ReservaController extends Controller
{
    // Método para mostrar la vista de crear reserva
    public function create()
    {
        // Obtener los clientes, horarios y servicios disponibles
        $clientes = Cliente::where('eliminado', 1)->get();
        $horarios = Horario::where('eliminado', 1)->get();
        $servicios = Servicio::with('horarios')->where('eliminado', 1)->get();

        return view('admin.reservas.create', compact('clientes', 'horarios', 'servicios'));
    }

    public function buscarCliente(Request $request)
    {
        $query = $request->get('query');

        // Buscar clientes por nombre
        $clientes = Cliente::where('nombre', 'like', "%{$query}%")->get();

        return response()->json($clientes);
    }

    // Asegúrate de importar la clase de PDF

    public function store(Request $request)
    {
        // Validar la entrada
        $validated = $request->validate([
            'idCliente' => 'required|exists:clientes,idCliente',
            'horarios' => 'required|array|min:1',
            'horarios.*' => 'exists:horarios,idHorario',
            'cantidad' => 'required|array|min:1',
            'cantidad.*' => 'required|integer|min:1',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        if (count($validated['horarios']) !== count($validated['cantidad'])) {
            return back()->withErrors('La cantidad de horarios y cantidades no coincide.');
        }

        DB::beginTransaction();
        $horariosAfectados = [];

        try {
            $reserva = new Reserva();
            $reserva->idCliente = $validated['idCliente'];
            $now = Carbon::now();
            $reserva->fechaReserva = $now;
            $reserva->total = 0;
            $reserva->descuento = $validated['descuento'] ?? 0;
            $reserva->estado = 'pendiente';
            $reserva->idAutor = auth()->id();
            $reserva->save();

            $totalReserva = 0;

            foreach ($validated['horarios'] as $index => $idHorario) {
                $horario = Horario::findOrFail($idHorario);
                $cantidad = $validated['cantidad'][$index];

                if ($horario->capacidad < $cantidad) {
                    throw new Exception("No hay suficiente capacidad para el horario {$horario->idHorario}.");
                }

                $horariosAfectados[] = ['horario' => $horario, 'cantidad' => $cantidad];

                $precioUnitario = $horario->servicio->precio;
                $subtotal = $precioUnitario * $cantidad;

                $detalleReserva = new DetalleReserva();
                $detalleReserva->idReserva = $reserva->idReserva;
                $detalleReserva->idHorario = $idHorario;
                $detalleReserva->cantidad = $cantidad;
                $detalleReserva->precioUnitario = $precioUnitario;
                $detalleReserva->subtotal = $subtotal;
                $detalleReserva->save();

                $totalReserva += $subtotal;

                $horario->capacidad -= $cantidad;
                $horario->save();
            }

            if ($reserva->descuento > $totalReserva) {
                throw new Exception('El descuento no puede ser mayor que el total.');
            } else {
                $totalReserva -= $reserva->descuento;
            }

            $reserva->total = $totalReserva;
            $reserva->save();

            if ($request->has('estadoPago') && $request->estadoPago === 'completado') {
                $pago = new Pago();
                $pago->idReserva = $reserva->idReserva;
                $pago->monto = $totalReserva;
                $pago->fechaPago = $now;
                $pago->estadoPago = 'completado';
                $pago->idAutor = auth()->id();
                $pago->save();

                $reserva->estado = 'pagado';
                $reserva->save();
            }

            DB::commit();

            // Generar el PDF del comprobante
            $pdf = Pdf::loadView('admin.reservas.comprobante', compact('reserva'));
            $pdfName = 'comprobante_reserva_' . $reserva->idReserva . '.pdf';

            // Descargar el PDF directamente
            return $pdf->download($pdfName);

        } catch (Exception $e) {
            DB::rollBack();

            foreach ($horariosAfectados as $registro) {
                $registro['horario']->capacidad += $registro['cantidad'];
                $registro['horario']->save();
            }

            return back()->withErrors('Error al crear la reserva: ' . $e->getMessage());
        }
    }
    public function generarComprobante($id)
    {
        // Cargar la reserva con los detalles, horarios y servicios relacionados
        $reserva = Reserva::with('cliente', 'detalles.horario.servicio')->findOrFail($id);
    
        $pdf = Pdf::loadView('admin.reservas.comprobante', compact('reserva'));
        return $pdf->download('comprobante_reserva_' . $reserva->idReserva . '.pdf');
    }
    



    // Método para mostrar todas las reservas
    public function index(Request $request)
    {
        $query = Reserva::with('cliente', 'detalleReservas.horario.servicio');

        // Filtro por estado
        $estado = $request->input('estado');
        if ($estado && $estado != 'todos') {
            $query->where('estado', $estado);
        }

        // Filtro por fecha
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fechaReserva', [$request->input('fecha_inicio'), $request->input('fecha_fin')]);
        }

        // Filtro por cliente
        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->input('cliente') . '%');
            });
        }

        $reservas = $query->get();

        // Estadísticas
        $totalReservas = Reserva::count();
        $reservasPagadas = Reserva::where('estado', 'pagado')->count();
        $reservasPendientes = Reserva::where('estado', 'pendiente')->count();

        return view('admin.reservas.index', compact('reservas', 'totalReservas', 'reservasPagadas', 'reservasPendientes'));
    }


    public function actualizarEstado(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = $request->estado; // Nueva información de estado (e.g., pagado, pendiente, cancelado)
        $reserva->save();

        return redirect()->route('admin.reservas.index')->with('success', 'Estado de reserva actualizado correctamente');
    }

    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = 'cancelado';  // Cambiar estado a cancelado
        $reserva->eliminado = 0;  // Marcar la reserva como cancelada (eliminación lógica)
        $reserva->save();

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva cancelada correctamente');
    }

}