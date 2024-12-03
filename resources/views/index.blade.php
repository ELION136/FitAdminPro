@extends('layouts.login')

@section('title', 'Escaneo de QR - FitAdminPro')

@section('content')
<div class="auth-one-bg-position d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-sm-4 shadow p-3" style="background-color: #ffffff; border-radius: 10px;">
        <h5 class="text-center mb-3">Escanear código QR</h5>
        
        <!-- Sección de Escaneo con Cámara -->
        <div class="row text-center mb-3">
            <a id="btn-scan-qr" href="#">
                <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg"
                     class="img-fluid text-center" width="100" alt="Escanear QR">
            </a>
            <canvas hidden id="qr-canvas" class="img-fluid" style="width: 100%; height: 200px; max-width: 300px; margin: 0 auto; border: 1px solid #ccc; border-radius: 8px;"></canvas>
        </div>
        
        <div class="row justify-content-center my-2">
            <div class="camera-controls text-center">
                <button class="btn btn-success btn-sm rounded-3 mb-2 me-2" onclick="encenderCamara()">Encender cámara</button>
                <button class="btn btn-danger btn-sm rounded-3" onclick="cerrarCamara()">Detener cámara</button>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="styled-button" style="--clr:#0FF0FC;">
                <span>Iniciar Sesión</span>
                <i></i>
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsqrcode/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const successSound = new Audio("{{ asset('assets/sonido.mp3') }}");
    const video = document.createElement("video");
    const canvasElement = document.getElementById("qr-canvas");
    const canvas = canvasElement.getContext("2d");
    const btnScanQR = document.getElementById("btn-scan-qr");

    let scanning = false;
    let selectedDeviceId = null;

    // Obtener cámaras disponibles
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
            encenderCamara();
        });

        selectedDeviceId = videoDevices[0]?.deviceId || null;
    };

    getCameras();

    const encenderCamara = () => {
        if (!selectedDeviceId) {
            Swal.fire("Error", "No se encontró ninguna cámara.", "error");
            return;
        }

        navigator.mediaDevices.getUserMedia({
            video: { deviceId: { exact: selectedDeviceId }}
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
            Swal.fire("Error", "Error al acceder a la cámara. Por favor, comprueba los permisos.", "error");
        });
    };

    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvasElement.height = video.videoHeight / 4; // Reduce el tamaño
            canvasElement.width = video.videoWidth / 4; // Reduce el tamaño
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

            canvas.strokeStyle = "red";
            canvas.lineWidth = 2;
            canvas.strokeRect(0, 0, canvasElement.width, canvasElement.height);
        }

        scanning && requestAnimationFrame(tick);
    }

    function scan() {
        try {
            qrcode.decode();
        } catch (e) {
            console.log("Error de escaneo QR:", e);
            setTimeout(scan, 100);
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

            axios.post('{{ route('asistencias.registrarQRb') }}', {
                idCliente: idCliente ? idCliente.trim() : null,
                tipoProducto: tipoProducto ? tipoProducto.trim() : null,
                idDetalle: idDetalle ? idDetalle.trim() : null,
                _token: '{{ csrf_token() }}'
            }).then(response => {
                Swal.fire('Asistencia registrada', response.data.success, 'success');
                successSound.play();
            }).catch(error => {
                Swal.fire('Error', error.response.data.error, 'error');
            });

            cerrarCamara();
        }
    };
</script>
@endpush
