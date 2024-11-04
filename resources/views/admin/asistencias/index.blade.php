@extends('layouts.app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-sm-6 shadow p-3" style="background-color: #f8f9fa; border-radius: 10px;">
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
                <div class="camera-controls"></div>
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
        const video = document.createElement("video");
        const canvasElement = document.getElementById("qr-canvas");
        const canvas = canvasElement.getContext("2d");
        const btnScanQR = document.getElementById("btn-scan-qr");
        const debugButton = document.getElementById("debug-button");

        const uploadInput = document.getElementById("upload-qr");
        const uploadCanvasElement = document.getElementById("upload-qr-canvas");
        const uploadCanvas = uploadCanvasElement.getContext("2d");

        let scanning = false;
        let selectedDeviceId = null;

        // Obtener cámaras disponibles y actualizar select de cámaras
        const getCameras = async () => {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(device => device.kind === 'videoinput');

            const cameraSelect = document.createElement("select");
            cameraSelect.className = "form-control mt-2";
            cameraSelect.id = "cameraSelect";

            videoDevices.forEach((device, index) => {
                const option = document.createElement("option");
                option.value = device.deviceId;
                option.text = device.label || `Cámara ${index + 1}`;
                cameraSelect.appendChild(option);
            });

            document.querySelector(".camera-controls").appendChild(cameraSelect);

            cameraSelect.addEventListener("change", (event) => {
                selectedDeviceId = event.target.value;
                cerrarCamara();
                encenderCamara(); // Vuelve a encender la cámara seleccionada
            });

            selectedDeviceId = videoDevices[0]?.deviceId || null;
        };

        // Llama a la función para obtener las cámaras cuando la página carga
        getCameras();

        // Función para encender la cámara seleccionada
        const encenderCamara = () => {
            if (!selectedDeviceId) {
                Swal.fire("Error", "No se encontró ninguna cámara.", "error");
                return;
            }

            navigator.mediaDevices
                .getUserMedia({
                    video: {
                        deviceId: {
                            exact: selectedDeviceId
                        }
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

        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

                canvas.strokeStyle = "red";
                canvas.lineWidth = 5;
                canvas.strokeRect(0, 0, canvasElement.width, canvasElement.height);
            }

            scanning && requestAnimationFrame(tick);
        }

        function scan() {
            try {
                qrcode.decode();
            } catch (e) {
                console.log("Error de escaneo QR:", e);
                setTimeout(scan, 100); // Aumenta la frecuencia de escaneo
            }
        }

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

        qrcode.callback = (respuesta) => {
            if (respuesta) {
                const [idCliente, tipoProducto, idDetalle] = respuesta.split(',');

                console.log("Datos recibidos del QR:", idCliente, tipoProducto,
                idDetalle); // Verifica que los valores sean correctos

                axios.post('{{ route('admin.asistencias.registrarQR') }}', {
                        idCliente: idCliente ? idCliente.trim() : null,
                        tipoProducto: tipoProducto ? tipoProducto.trim() : null,
                        idDetalle: idDetalle ? idDetalle.trim() : null,
                        _token: '{{ csrf_token() }}'
                    })
                    .then(response => {
                        Swal.fire('Asistencia registrada', response.data.success, 'success');
                    })
                    .catch(error => {
                        Swal.fire('Error', error.response.data.error, 'error');
                    });

                cerrarCamara();
            }
        };


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
                    uploadCanvasElement.width = img.width;
                    uploadCanvasElement.height = img.height;
                    uploadCanvas.drawImage(img, 0, 0, img.width, img.height);

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

        debugButton.onclick = () => {
            console.log("Dimensiones del video:", video.videoWidth, "x", video.videoHeight);
            console.log("Dimensiones del canvas:", canvasElement.width, "x", canvasElement.height);
            console.log("Estado de escaneo:", scanning);
        };
    </script>
@endpush
