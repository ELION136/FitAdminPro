@extends('layouts.admin')

@section('content')

<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Lista de empleados</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a>listas</a></li><i class="bi bi-three-dots-vertical"></i>
                <li aria-current="page">empleados</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex my-xl-auto right-content align-items-center">
        
        <div class="mb-xl-0">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
                    14 Aug 2019
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate">
                    <li><a class="dropdown-item" href="javascript:void(0);">2015</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2016</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2017</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);">2018</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Page Header Close -->

<!-- Start::row-1 -->
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">Empleados </h4> <br>
                    <a href="javascript:void(0);" class="tx-inverse" data-bs-toggle="dropdown"><i class="mdi mdi-dots-horizontal text-gray"></i></a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="javascript:void(0);">Action</a>
                        <a class="dropdown-item" href="javascript:void(0);">Another Action</a>
                        <a class="dropdown-item" href="javascript:void(0);">Something Else Here</a>
                    </div>
                </div>
                <a href="{{route('admin.empleados.create')}}" class="btn btn-primary rounded-pill btn-wave" data-bs-original-title="Añadir">
                    <i class="bi bi-clipboard-check"></i> Añadir un nuevo Empleado
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="file-export" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th><span>Foto de Perfil</span></th>
                                <th><span>Nombre</span></th> 
                                <th><span>Correo Electronico</span></th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleados as $empleado)
                            <tr>
                                <td>
                                    @if($empleado->usuario->image)
                                        <img src="{{ asset('storage/' . $empleado->usuario->image) }}" alt="Foto de perfil" width="50" height="50">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil" width="50" height="50">
                                    @endif
                                </td>
                                <td>{{ $empleado->nombre }} {{ $empleado->primerApellido }} {{ $empleado->segundoApellido }}</td>
                                
                                <td>{{ $empleado->usuario->email }}</td>
                                <td>
                                    
                                    <a href="{{route('admin.empleados.edit', $empleado->idEmpleado)}}"  class="btn btn-sm btn-info btn-b" data-bs-toggle="tooltip" title="" data-bs-original-title="Editar">
                                        <i class="bi bi-pen-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.empleados.destroy', $empleado->idEmpleado) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
</div>
<!--End::row-1 -->


@endsection
