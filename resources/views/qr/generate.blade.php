<!-- resources/views/qr/scanner.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container text-center">
        <h1>Escanear código QR</h1>
        <div class="video-container">
            <video id="preview" style="width: 100%; height: auto;"></video>
        </div>
        <div class="controls mt-3">
            <button id="start-camera" class="btn btn-success">Encender Cámara</button>
            <button id="stop-camera" class="btn btn-danger">Apagar Cámara" disabled>Apagar Cámara</button>
        </div>
        <div id="result" class="mt-4"></div>

        <!-- Instascan JS -->
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script>
            let scanner;
            let activeCamera;

            document.addEventListener('DOMContentLoaded', function () {
                scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

                // Evento cuando se escanea algo
                scanner.addListener('scan', function (content) {
                    // Enviar el QR escaneado al servidor
                    fetch('/check', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ qr_data: content })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            document.getElementById('result').innerHTML = `<p class="text-danger">${data.error}</p>`;
                        } else {
                            document.getElementById('result').innerHTML = `<p class="text-success">${data.message} para ${data.nombre}</p>`;
                        }
                    })
                    .catch(error => {
                        // Mostrar el mensaje de error en caso de fallo
                        document.getElementById('result').innerHTML = `<p class="text-danger">Error al procesar el código QR</p>`;
                        console.error('Error:', error);
                    });
                });

                // Encender cámara
                document.getElementById('start-camera').addEventListener('click', function () {
                    Instascan.Camera.getCameras().then(function (cameras) {
                        if (cameras.length > 0) {
                            activeCamera = cameras[0];
                            scanner.start(activeCamera);
                            document.getElementById('start-camera').disabled = true;
                            document.getElementById('stop-camera').disabled = false;
                            document.getElementById('result').innerHTML = '<p class="text-success">Cámara encendida</p>';
                        } else {
                            document.getElementById('result').innerHTML = '<p class="text-danger">No se encontraron cámaras</p>';
                        }
                    }).catch(function (e) {
                        console.error(e);
                    });
                });

                // Apagar cámara
                document.getElementById('stop-camera').addEventListener('click', function () {
                    if (scanner) {
                        scanner.stop();
                        document.getElementById('start-camera').disabled = false;
                        document.getElementById('stop-camera').disabled = true;
                        document.getElementById('result').innerHTML = '<p class="text-danger">Cámara apagada</p>';
                    }
                });
            });
        </script>
    </div>
@endsection
