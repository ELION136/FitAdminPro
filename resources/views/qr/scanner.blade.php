@extends('layouts.admin')

@section('content')
    <div class="container text-center">
        <h1>Escanear código QR</h1>
        <div class="video-container">
            <video id="preview" style="width: 50%; height: auto;"></video>
        </div>
        <div class="controls mt-3">
            <button id="start-camera" class="btn btn-success">Encender Cámara</button>
            <button id="stop-camera" class="btn btn-danger">Apagar Cámara</button>
        </div>
        <div id="result" class="mt-4"></div>

        <!-- Agrega la biblioteca de Instascan -->
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script>
            let scanner;
            let activeCamera;

            document.addEventListener('DOMContentLoaded', function () {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    document.getElementById('result').innerHTML = '<p class="text-danger">Tu navegador no es compatible con el acceso a la cámara.</p>';
                    return;
                }

                scanner = new Instascan.Scanner({ 
                    video: document.getElementById('preview'), 
                    scanPeriod: 5,
                    mirror: false  // Desactivamos el efecto espejo para la cámara frontal
                });

                scanner.addListener('scan', function (content) {
                    document.getElementById('result').innerHTML = '<p class="text-info">Procesando código QR...</p>';
                    
                    fetch('{{ route('qr.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ qr_data: content })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Respuesta de red no fue ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('result').innerHTML = `<p class="text-success">${data.message} para ${data.nombre}</p>`;
                    })
                    .catch(error => {
                        document.getElementById('result').innerHTML = `<p class="text-danger">Error al procesar el código QR: ${error.message}</p>`;
                        console.error('Error:', error);
                    });
                });

                document.getElementById('start-camera').addEventListener('click', function () {
                    Instascan.Camera.getCameras().then(function (cameras) {
                        if (cameras.length > 0) {
                            activeCamera = cameras[0];  // Usamos la primera cámara disponible (normalmente la frontal)
                            scanner.start(activeCamera);
                            document.getElementById('result').innerHTML = '<p class="text-success">Cámara encendida. Apunta al código QR.</p>';
                        } else {
                            document.getElementById('result').innerHTML = '<p class="text-danger">No se encontraron cámaras</p>';
                        }
                    }).catch(function (e) {
                        document.getElementById('result').innerHTML = `<p class="text-danger">Error al acceder a la cámara: ${e.message}</p>`;
                        console.error(e);
                    });
                });

                document.getElementById('stop-camera').addEventListener('click', function () {
                    if (scanner) {
                        scanner.stop();
                        document.getElementById('result').innerHTML = '<p class="text-danger">Cámara apagada</p>';
                    }
                });
            });
        </script>
    </div>
@endsection