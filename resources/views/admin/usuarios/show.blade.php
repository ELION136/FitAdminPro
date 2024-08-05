@extends('layouts.admin')
@section('content')
    <!--Panel de contenido-->

    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin/perfil') }}">Perfil</a></li>
        <li class="breadcrumb-item active">Datos Personales</li>
    </ol>

    <h1 class="page-header">
        <p>{{ $usuario->name }}</p>
    </h1>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Datos del Usuario</h4>
        </div>
        <div class="card-body">
            <div class="invoice">
                <div class="invoice-company">
                    <span class="float-end hidden-print">
                        <a href="javascript:;" onclick="generatePDF()" class="btn btn-sm btn-white mb-10px">
                            <i class="fa fa-file-pdf t-plus-1 text-danger fa-fw fa-lg"></i> Exportar como PDF
                        </a>
                        <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-white mb-10px">
                            <i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Imprimir
                        </a>
                    </span>
                    Datos Registrados
                </div>
                <div class="invoice-content">
                    <div class="panel-body">
                        <div class="user-card">
                            <h3>Usuario: {{ $usuario->name }}</h3>
                            <div class="mb-3">
                                <label class="form-label">Email</label><br>
                                <p>{{ $usuario->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre:</label><br>
                                <p>{{ $usuario->name }}</p>
                            </div>
                            <a href="{{ url('admin/usuarios/edit', $usuario->id) }}" class="btn btn-primary w-100px me-5px">Modificar</a>
                            <a href="{{url('admin/usuarios')}}" class="btn btn-default w-100px">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end panel de contenido-->
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script>
    function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text("Datos del Usuario", 10, 10);
        doc.text(`Nombre: {{ $usuario->name }}`, 10, 20);
        doc.text(`Email: {{ $usuario->email }}`, 10, 30);

        doc.save("Datos_Usuario.pdf");
    }
</script>
@endpush
