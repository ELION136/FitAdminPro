<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inscripcion;
use Carbon\Carbon;

class ActualizarEstadoMembresias extends Command
{
   // El nombre y la firma del comando
   protected $signature = 'membresias:actualizar-estado';

   // La descripción del comando
   protected $description = 'Actualiza el estado de las membresías vencidas a "vencida"';

   // El cuerpo del comando
   public function handle()
   {
       $hoy = Carbon::today();

       // Actualizar todas las membresías activas cuya fechaFin es anterior a hoy
       $membresiasActualizadas = Inscripcion::where('tipoProducto', 'membresia')
           ->where('estado', 'activa')
           ->whereDate('fechaFin', '<', $hoy)
           ->update(['estado' => 'vencida']);

       // Mostrar mensaje en la consola
       $this->info("Actualización completada. Membresías actualizadas: {$membresiasActualizadas}");
   }
}
