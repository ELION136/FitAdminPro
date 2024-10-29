@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-sm-6 shadow p-3">
        <h5 class="text-center">Escanear código QR</h5>
        <!-- Sección de Escaneo con Cámara -->
        <div class="row text-center mb-4">
            <a id="btn-scan-qr" href="#">
                <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg"
                    class="img-fluid text-center" width="175" alt="Escanear QR">
            </a>
            <canvas hidden id="qr-canvas" class="img-fluid"></canvas>
        </div>
        <div class="row mx-5 my-3">
            <button class="btn btn-success btn-sm rounded-3 mb-2" onclick="encenderCamara()">Encender cámara</button>
            <button class="btn btn-danger btn-sm rounded-3" onclick="cerrarCamara()">Detener cámara</button>
        </div>

        <hr>

        <!-- Sección de Escaneo con Imagen -->
        <h5 class="text-center mt-4">O subir una imagen con código QR</h5>
        <div class="row text-center">
            <input type="file" id="upload-qr" accept="image/*" class="form-control-file mt-2">
        </div>
        <div class="row text-center mt-3">
            <canvas hidden id="upload-qr-canvas" class="img-fluid"></canvas>
        </div>
        <div class="row mx-5 my-3">
            <button class="btn btn-primary btn-sm rounded-3" onclick="scanUploadedImage()">Escanear Imagen</button>
        </div>

        <div class="row mx-5 my-3">
            <button id="debug-button" class="btn btn-info btn-sm rounded-3">Registrar Info de Depuración</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsqrcode/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Variables para escaneo con cámara
    const video = document.createElement("video");
    const canvasElement = document.getElementById("qr-canvas");
    const canvas = canvasElement.getContext("2d");
    const btnScanQR = document.getElementById("btn-scan-qr");
    const debugButton = document.getElementById("debug-button");

    // Variables para escaneo con imagen subida
    const uploadInput = document.getElementById("upload-qr");
    const uploadCanvasElement = document.getElementById("upload-qr-canvas");
    const uploadCanvas = uploadCanvasElement.getContext("2d");

    let scanning = false;

    // Función para encender la cámara
    const encenderCamara = () => {
        navigator.mediaDevices
            .getUserMedia({
                video: {
                    facingMode: "environment"
                }
            })
            .then(function(stream) {
                scanning = true;
                btnScanQR.hidden = true;
                canvasElement.hidden = false;
                video.setAttribute("playsinline", true);
                video.srcObject = stream;
                video.play();
                tick();
                scan();
            })
            .catch(function(error) {
                console.error("Error al acceder a la cámara:", error);
                Swal.fire("Error", "Error al acceder a la cámara. Por favor, comprueba los permisos.", "error");
            });
    };

    // Función para dibujar en el canvas y actualizar
    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

            // Debug: Dibujar un borde alrededor del canvas
            canvas.strokeStyle = "red";
            canvas.lineWidth = 5;
            canvas.strokeRect(0, 0, canvasElement.width, canvasElement.height);
        }

        scanning && requestAnimationFrame(tick);
    }

    // Función para iniciar el escaneo
    function scan() {
        try {
            qrcode.decode();
        } catch (e) {
            console.log("Error de escaneo QR:", e);
            setTimeout(scan, 100);  // Frecuencia aumentada
        }
    }

    // Función para cerrar la cámara
    const cerrarCamara = () => {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach((track) => {
                track.stop();
            });
        }
        scanning = false;
        canvasElement.hidden = true;
        btnScanQR.hidden = false;
    };

    // Callback cuando se detecta un QR
    qrcode.callback = (respuesta) => {
        if (respuesta) {
            console.log("Código QR detectado:", respuesta);
            // Llamada al backend para registrar la asistencia
            axios.post('{{ route('admin.asistencias.registrarQR') }}', {
                dataQR: respuesta
            }).then(response => {
                Swal.fire('Asistencia registrada', response.data.success, 'success');
            }).catch(error => {
                Swal.fire('Error', error.response.data.error, 'error');
            });
            cerrarCamara();
        }
    };

    // Función para manejar el escaneo de imagen subida
    const scanUploadedImage = () => {
        const file = uploadInput.files[0];
        if (!file) {
            Swal.fire('Error', 'Por favor, selecciona una imagen primero.', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                // Ajustar el canvas al tamaño de la imagen
                uploadCanvasElement.width = img.width;
                uploadCanvasElement.height = img.height;
                uploadCanvas.drawImage(img, 0, 0, img.width, img.height);
                uploadCanvas.strokeStyle = "blue";
                uploadCanvas.lineWidth = 5;
                uploadCanvas.strokeRect(0, 0, uploadCanvasElement.width, uploadCanvasElement.height);

                try {
                    qrcode.decode(uploadCanvasElement.toDataURL());
                } catch (e) {
                    console.log("Error al escanear la imagen subida:", e);
                    Swal.fire('Error', 'No se pudo escanear el código QR en la imagen.', 'error');
                }
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    };

    // Función para registrar información de depuración
    debugButton.onclick = () => {
        console.log("Dimensiones del video:", video.videoWidth, "x", video.videoHeight);
        console.log("Dimensiones del canvas:", canvasElement.width, "x", canvasElement.height);
        console.log("Estado de escaneo:", scanning);
    };
</script>
@endpush
