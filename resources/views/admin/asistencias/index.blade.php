@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Escanear código QR</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center mb-4">
                            <a id="btn-scan-qr" href="#">
                                <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg" 
                                     class="img-fluid" width="175" alt="Escanear QR">
                            </a>
                            <canvas hidden id="qr-canvas" class="img-fluid w-100"></canvas>
                        </div>

                        <div class="mb-3">
                            <div class="camera-controls mb-2"></div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success flex-grow-1 rounded-pill" onclick="encenderCamara()">
                                    <i class="ri-camera-line align-middle me-1"></i> Encender cámara
                                </button>
                                <button class="btn btn-danger flex-grow-1 rounded-pill" onclick="cerrarCamara()">
                                    <i class="ri-camera-off-line align-middle me-1"></i> Detener cámara
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-center mb-3">
                            <h5>Subir imagen con código QR</h5>
                            <input type="file" id="upload-qr" accept="image/*" class="form-control my-2">
                            <canvas hidden id="upload-qr-canvas" class="img-fluid w-100"></canvas>
                        </div>
                        <button class="btn btn-primary w-100 rounded-pill" onclick="scanUploadedImage()">
                            <i class="ri-upload-line align-middle me-1"></i> Escanear Imagen
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <button id="debug-button" class="btn btn-outline-info w-100 rounded-pill">
                        <i class="ri-bug-line align-middle me-1"></i> Información de Depuración
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsqrcode/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const successSound = new Audio("{{ asset('assets/sonido.mp3') }}");
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

    const getCameras = async () => {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        const cameraSelect = document.createElement("select");
        
        cameraSelect.className = "form-select mb-2";
        cameraSelect.id = "cameraSelect";

        videoDevices.forEach((device, index) => {
            const option = document.createElement("option");
            option.value = device.deviceId;
            option.text = device.label || `Cámara ${index + 1}`;
            cameraSelect.appendChild(option);
        });

        document.querySelector(".camera-controls").appendChild(cameraSelect);
        selectedDeviceId = videoDevices[0]?.deviceId || null;

        cameraSelect.addEventListener("change", (event) => {
            selectedDeviceId = event.target.value;
            cerrarCamara();
            encenderCamara();
        });
    };

    getCameras();

    const encenderCamara = () => {
        if (!selectedDeviceId) {
            Swal.fire("Error", "No se encontró ninguna cámara.", "error");
            return;
        }

        navigator.mediaDevices.getUserMedia({
            video: { deviceId: { exact: selectedDeviceId } }
        }).then(function(stream) {
            scanning = true;
            btnScanQR.hidden = true;
            canvasElement.hidden = false;
            video.setAttribute("playsinline", true);
            video.srcObject = stream;
            video.play();
            tick();
            scan();
        }).catch(function(error) {
            console.error("Error al acceder a la cámara:", error);
            Swal.fire("Error", "Error al acceder a la cámara. Verifica los permisos.", "error");
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
            setTimeout(scan, 100);
        }
    }

    const cerrarCamara = () => {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach((track) => track.stop());
        }
        scanning = false;
        canvasElement.hidden = true;
        btnScanQR.hidden = false;
    };

    qrcode.callback = (respuesta) => {
        if (respuesta) {
            const [idCliente, tipoProducto, idDetalle] = respuesta.split(',');

            axios.post('{{ route('admin.asistencias.registrarQR') }}', {
                idCliente: idCliente ? idCliente.trim() : null,
                tipoProducto: tipoProducto ? tipoProducto.trim() : null,
                idDetalle: idDetalle ? idDetalle.trim() : null,
                _token: '{{ csrf_token() }}'
            }).then(response => {
                Swal.fire('Éxito', response.data.success, 'success');
                successSound.play();
            }).catch(error => {
                Swal.fire('Error', error.response.data.error, 'error');
            });

            cerrarCamara();
        }
    };

    const scanUploadedImage = () => {
        const file = uploadInput.files[0];
        if (!file) {
            Swal.fire('Error', 'Selecciona una imagen primero.', 'error');
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
                    Swal.fire('Error', 'No se pudo escanear el código QR.', 'error');
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