@extends('layouts.admin')
@section('content')
    <!-- Start::app-content -->
    
            <!-- Page Header -->
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div>
                    <h4 class="mb-0">Bienvenido a FitAdminPro</h4>
                    <p class="mb-0 text-muted"></p>
                </div>
                
            </div>
            <!-- End Page Header -->
             <!-- Start::row-1 -->
             <div class="row">
                <div class="col-lg-6 col-xl-3 col-md-6 col-12">
                    <div class="card bg-primary-gradient text-fixed-white ">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="col-6">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fe fe-users fs-40"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mt-0 text-center">
                                        <span class="text-fixed-white">USUARIOS</span>
                                        <h3 class="text-fixed-white mb-0">{{$total}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 col-md-6 col-12">
                    <div class="card bg-danger-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="col-6">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fe fe-shopping-cart fs-40"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mt-0 text-center">
                                        <span class="text-fixed-white">Sales</span>
                                        <h3 class="text-fixed-white mb-0">854</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 col-md-6 col-12">
                    <div class="card bg-success-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="col-6">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fe fe-bar-chart-2 fs-40"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mt-0 text-center">
                                        <span class="text-fixed-white">Profits</span>
                                        <h3 class="text-fixed-white mb-0">98K</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 col-md-6 col-12">
                    <div class="card bg-warning-gradient text-fixed-white">
                        <div class="card-body text-fixed-white">
                            <div class="row">
                                <div class="col-6">
                                    <div class="icon1 mt-2 text-center">
                                        <i class="fe fe-pie-chart fs-40"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mt-0 text-center">
                                        <span class="text-fixed-white">Taxes</span>
                                        <h3 class="text-fixed-white mb-0">876</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->

            
            <!-- row closed -->


            <!-- Start::row-1 -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Line Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-line" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Bar Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-bar" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Pie Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-pie" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Doughnut Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-doughnut" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Mixed Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-mixed" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Polar Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-polar" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Radial Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-radar" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Scatter Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-scatter" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Chartjs Bubble Chart</div>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs-bubble" class="chartjs-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--End::row-1 -->
    <!-- End::app-content -->
        <!-- row closed -->
    @endsection
