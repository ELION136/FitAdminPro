@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Códigos QR para todos los clientes</h1>
    <div class="row">
        @foreach($clientesConQR as $item)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $item['cliente']->nombre }}</h5>
                    <div class="text-center">
                        {!! $item['qrCode'] !!}
                    </div>
                    <p class="card-text mt-2">ID: {{ $item['cliente']->idCliente }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button onclick="window.print()" class="btn btn-primary mt-3">Imprimir todos los QR</button>
</div>
@endsection