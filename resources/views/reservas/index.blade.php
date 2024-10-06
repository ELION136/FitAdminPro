@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4 mb-4">Gestión de Reservas</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Reservas</h5>
            <a href="{{ route('admin.reservas.create') }}" class="btn btn-light">
                <i class="fas fa-plus-circle"></i> Nueva Reserva
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha de Reserva</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->idReserva }}</td>
                                <td>{{ $reserva->cliente->nombre }} {{ $reserva->cliente->primerApellido }}</td>
                                <td>{{ $reserva->fechaReserva->format('d/m/Y H:i') }}</td>
                                <td>${{ number_format($reserva->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $reserva->estado == 'pendiente' ? 'warning' : ($reserva->estado == 'pagado' ? 'success' : 'danger') }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('reservas.show', $reserva->idReserva) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('reservas.edit', $reserva->idReserva) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('reservas.destroy', $reserva->idReserva) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Cancelar reserva" onclick="return confirm('¿Está seguro de que desea cancelar esta reserva?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay reservas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $reservas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endpush

@push('scripts')
<script>
    // Aquí puedes agregar cualquier JavaScript específico para esta vista
</script>
@endpush