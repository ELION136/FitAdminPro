
@extends('layouts.admin')

@section('content')
<div class="container my-5">
    @if ($membresia)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Detalles de tu Membresía</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Información del cliente -->
                    <div class="col-md-4 text-center">
                        @if (Auth::user()->image)
                                                <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                                    alt="Foto de perfil" width="150" height="150">
                                            @else
                                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil"
                                                    width="150" height="150">
                                            @endif
                        <h5>{{ Auth::user()->cliente->nombre }} {{ Auth::user()->cliente->primerApellido }}</h5>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <!-- Información de la membresía -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h6 class="text-primary">Membresía:</h6>
                            <p class="mb-0"><strong>{{ $membresia->membresia->nombre }}</strong></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary">Duración:</h6>
                            <p class="mb-0">Desde: {{ $membresia->fechaInicio }}<br>Hasta: {{ $membresia->fechaFin }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary">Estado:</h6>
                            <p class="mb-0">{{ ucfirst($membresia->estado) }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-primary">Monto Pagado:</h6>
                            <p class="mb-0">${{ number_format($membresia->montoPago, 2) }}</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-primary">Fecha del Pago:</h6>
                            <p class="mb-0">{{ $membresia->fechaPago ? $membresia->fechaPago->format('d/m/Y') : 'No disponible' }}</p>
                        </div>

                        <a href="{{ route('cliente.membresias.credencial') }}" class="btn btn-primary mt-3">
                            Imprimir Credencial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center" role="alert">
            No tienes una membresía activa en este momento.
        </div>
        <div class="text-center">
            <a href="{{ route('cliente.membresias.solicitar') }}" class="btn btn-success">Inscribirse a una Membresía</a>
        </div>
    @endif
</div>
@endsection