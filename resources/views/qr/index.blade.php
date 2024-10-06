<!-- resources/views/qr/show.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container text-center my-5">
        <h2 class="mb-4">Código QR para {{ $cliente->nombre }}</h2>
        <div class="card shadow-lg p-4 mb-4" style="border-radius: 10px;">
            <div class="mb-4">
                <!-- Aquí se muestra el código QR -->
                {!! $qrCode !!}
            </div>
            <p class="text-muted">Escanea este código para registrar tu asistencia.</p>
            <hr>
            <div class="form-group">
                <label for="custom-message">Mensaje Personalizado (Opcional):</label>
                <input type="text" id="custom-message" class="form-control" placeholder="Escribe un mensaje aquí...">
            </div>
            <button id="save-qr" class="btn btn-primary mt-3">Guardar QR</button>
        </div>

        <div id="result" class="mt-4"></div>
    </div>

    <!-- Agregar SweetAlert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('save-qr').addEventListener('click', function() {
            const customMessage = document.getElementById('custom-message').value;

            // Aquí puedes implementar la lógica para guardar el QR si es necesario
            // Por ahora solo mostramos un mensaje de éxito
            Swal.fire({
                title: 'Éxito!',
                text: 'Código QR guardado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
@endsection
