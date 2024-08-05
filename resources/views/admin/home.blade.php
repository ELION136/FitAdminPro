@extends('layouts.admin')
@section('content')
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div class="my-auto">
        <h5 class="page-title fs-21 mb-1">Pagina Inicial</h5>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inicio</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex my-xl-auto right-content align-items-center">
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-info btn-icon me-2 btn-b"><i class="mdi mdi-filter-variant"></i></button>
        </div>
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-danger btn-icon me-2"><i class="mdi mdi-star"></i></button>
        </div>
        <div class="pe-1 mb-xl-0">
            <button type="button" class="btn btn-warning  btn-icon me-2"><i class="mdi mdi-refresh"></i></button>
        </div>
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

<div class="col-sm-12 col-xxl-4 col-xl-12 col-lg-12">
    <div class="card user-wideget user-wideget-widget widget-user">
        <div class="widget-user-header bg-success text-fixed-white">
            <h3 class="widget-user-username">{{ $userName ?? 'Usuario' }}</h3>
            <h5 class="widget-user-desc text-fixed-white">{{auth()->user()->rol}}</h5>
        </div>
        <div class="widget-user-image">
            <img src="{{ auth()->user()->profile_image_url }}" class="rounded-circle" alt="User Avatar">
        </div>
        <div class="user-wideget-footer">
            <div class="row mx-0">
                <div class="col-sm-4 border-end">
                    <div class="description-block">
                        <h5 class="description-header">56%</h5>
                        <span class="description-text">RENDIMIENTO</span>
                    </div>
                </div>
                <div class="col-sm-4 border-end">
                    <div class="description-block">
                        <h5 class="description-header">70</h5>
                        <span class="description-text">VISITAS</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="description-block">
                        <h5 class="description-header">60%</h5>
                        <span class="description-text">ACTIVIDAD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End::row-2 -->
@endsection