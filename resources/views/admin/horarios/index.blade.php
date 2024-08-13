@extends('layouts.admin')
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Horarios Entrenador</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">pagina</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Horarios</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <button type="button" class="btn btn-info btn-icon me-2 btn-b"><i
                        class="mdi mdi-filter-variant"></i></button>
            </div>
            <div class="pe-1 mb-xl-0">
                <button type="button" class="btn btn-danger btn-icon me-2"><i class="mdi mdi-star"></i></button>
            </div>
            <div class="pe-1 mb-xl-0">
                <button type="button" class="btn btn-warning  btn-icon me-2"><i class="mdi mdi-refresh"></i></button>
            </div>
            <div class="mb-xl-0">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate"
                        data-bs-toggle="dropdown" aria-expanded="false">
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
        <div class="col-xl-3">
            <div class="card custom-card">
                <div class="card-header d-grid">
                    <button class="btn btn-primary-light btn-wave"><i
                            class="ri-add-line align-middle me-1 fw-semibold d-inline-block"></i>Create New Event</button>
                </div>
                <div class="card-body p-0">
                    <div id="external-events" class="p-3">
                        <div
                            class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-primary border border-primary">
                            <div class="fc-event-main">Calendar Events</div>
                        </div>
                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-secondary border border-secondary"
                            data-class="bg-secondary">
                            <div class="fc-event-main">Birthday EVents</div>
                        </div>
                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-success border border-success"
                            data-class="bg-success">
                            <div class="fc-event-main">Holiday Calendar</div>
                        </div>
                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-info border border-info"
                            data-class="bg-info">
                            <div class="fc-event-main">Office Events</div>
                        </div>
                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-warning border border-warning"
                            data-class="bg-warning">
                            <div class="fc-event-main">Other Events</div>
                        </div>
                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-danger border border-danger"
                            data-class="bg-danger">
                            <div class="fc-event-main">Festival Events</div>
                        </div>
                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-teal border border-teal"
                            data-class="bg-danger">
                            <div class="fc-event-main">Timeline Events</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Full Calendar</div>
                </div>
                <div class="card-body">
                    <div id='calendar2'></div>
                </div>
            </div>
        </div>
    </div>
    <!--End::row-1 -->
@endsection
