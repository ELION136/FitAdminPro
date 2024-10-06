<?php

namespace App\Http\Controllers;


use App\Models\Cliente;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class QRCheckController extends Controller
{



    public function generateAllQR()
    {
        $clientes = Cliente::all();
        $clientesConQR = [];

        foreach ($clientes as $cliente) {
            $data = [
                'id' => $cliente->idCliente,
                'timestamp' => now()->timestamp,
            ];
            
            $qrCode = QrCode::size(300)->generate(json_encode($data));
            
            $clientesConQR[] = [
                'cliente' => $cliente,
                'qrCode' => $qrCode
            ];
        }
        
        return view('qr.all', compact('clientesConQR'));
    }
    public function index()
    {
        $clientes = Cliente::all();
        return view('qr.index', compact('clientes'));
    }

    public function show()
    {
        return view('qr.scanner');
    }
    public function generateQR(Cliente $cliente)
{
    $data = [
        'id' => $cliente->idCliente,
        'timestamp' => now()->timestamp, // Genera un timestamp actual
    ];

    // Genera el código QR con los datos en formato JSON
    $qrCode = QrCode::size(300)->generate(json_encode($data));

    // Devuelve la vista con el código QR
    return view('qr.generate', compact('qrCode', 'cliente'));
}

    public function processCheck(Request $request)
    {
        $data = json_decode($request->input('qr_data'), true);

        if (!$data || !isset($data['id']) || !isset($data['timestamp'])) {
            return response()->json(['error' => 'QR inválido'], 400);
        }

        $cliente = Cliente::find($data['id']);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $lastCheck = Asistencia::where('idCliente', $cliente->idCliente)
            ->whereDate('fecha', Carbon::today())
            ->latest()
            ->first();

        if (!$lastCheck || $lastCheck->horaSalida) {
            // Check-in
            $asistencia = new Asistencia;
            $asistencia->idCliente = $cliente->idCliente;
            $asistencia->fecha = Carbon::today();
            $asistencia->horaEntrada = Carbon::now();
            $asistencia->save();

            return response()->json(['message' => 'Check-in exitoso', 'nombre' => $cliente->nombre]);
        } else {
            // Check-out
            $lastCheck->horaSalida = Carbon::now();
            $lastCheck->save();

            return response()->json(['message' => 'Check-out exitoso', 'nombre' => $cliente->nombre]);
        }
    }


    public function process(Request $request)
    {
        $data = json_decode($request->input('qr_data'), true);

        // Validar que el QR contenga los campos requeridos
        if (!$data || !isset($data['id']) || !isset($data['timestamp'])) {
            return response()->json(['error' => 'QR inválido'], 400);
        }
    
        $cliente = Cliente::find($data['id']);
    
        // Validar si el cliente existe
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    
        // Buscar si el cliente ya ha hecho check-in hoy
        $lastCheck = Asistencia::where('idCliente', $cliente->idCliente)
            ->whereDate('fecha', Carbon::today())
            ->latest()
            ->first();
    
        // Si no ha hecho check-in o ya hizo check-out, registrar check-in
        if (!$lastCheck || $lastCheck->horaSalida) {
            $asistencia = new Asistencia;
            $asistencia->idCliente = $cliente->idCliente;
            $asistencia->fecha = Carbon::today();
            $asistencia->horaEntrada = Carbon::now();
            $asistencia->save();
    
            return response()->json(['message' => 'Check-in exitoso', 'nombre' => $cliente->nombre]);
        } else {
            // Si ya hizo check-in pero no ha hecho check-out, registrar check-out
            $lastCheck->horaSalida = Carbon::now();
            $lastCheck->save();
    
            return response()->json(['message' => 'Check-out exitoso', 'nombre' => $cliente->nombre]);
        }
    }
}
