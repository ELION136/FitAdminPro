<!-- resources/views/admin/horarios/form.blade.php -->

<div class="form-group">
    <label for="servicio">Servicio</label>
    <input type="text" class="form-control" id="servicio" name="servicio" value="{{ $horario->servicio->nombre ?? 'Sin servicio' }}" readonly>
</div>

<div class="form-group">
    <label for="entrenador">Entrenador</label>
    <input type="text" class="form-control" id="entrenador" name="entrenador" value="{{ $horario->entrenador->nombre ?? 'Sin entrenador' }}" readonly>
</div>

<div class="form-group">
    <label for="diaSemana">Día de la Semana</label>
    <input type="text" class="form-control" id="diaSemana" name="diaSemana" value="{{ $horario->diaSemana ?? 'Sin entrenador'}}">
</div>

<div class="form-group">
    <label for="horaInicio">Hora de Inicio</label>
    <input type="time" class="form-control" id="horaInicio" name="horaInicio" value="{{ $horario->horaInicio ?? 'Sin entrenador' }}">
</div>

<div class="form-group">
    <label for="horaFin">Hora de Fin</label>
    <input type="time" class="form-control" id="horaFin" name="horaFin" value="{{ $horario->horaFin ?? 'Sin entrenador'}}">
</div>

<div class="form-group">
    <label for="capacidad">Capacidad</label>
    <input type="number" class="form-control" id="capacidad" name="capacidad" value="{{ $horario->capacidad ?? 'Sin entrenador' }}">
</div>
