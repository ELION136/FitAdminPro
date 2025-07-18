@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-12">
                        <div
                            class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                            <h4 class="mb-sm-0">Panel Principal</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                    <li class="breadcrumb-item active">Inicio</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    @php
                        $summaryCards = [
                            [
                                'title' => 'Ingresos Hoy',
                                'icon' => 'ri-wallet-line',
                                'value' => number_format($incomeToday, 2),
                                'color' => 'bg-success',
                                'subtitle' => 'Total diario',
                                'isIncome' => true,
                            ],
                            [
                                'title' => 'Ingresos Esta Semana',
                                'icon' => 'ri-calendar-line',
                                'value' => number_format($incomeThisWeek, 2),
                                'color' => 'bg-primary',
                                'subtitle' => 'Total semanal',
                                'isIncome' => true,
                            ],
                            [
                                'title' => 'Ingresos Este Mes',
                                'icon' => 'ri-bar-chart-line',
                                'value' => number_format($incomeThisMonth, 2),
                                'color' => 'bg-warning',
                                'subtitle' => 'Total mensual',
                                'isIncome' => true,
                            ],
                            [
                                'title' => 'Ingresos Totales',
                                'icon' => 'ri-bank-line',
                                'value' => number_format($totalIncome, 2),
                                'color' => 'bg-danger',
                                'subtitle' => 'Acumulado',
                                'isIncome' => true,
                            ],
                            [
                                'title' => 'Membresías Activas',
                                'icon' => 'ri-group-line',
                                'value' => $totalMembresiasActivas,
                                'color' => 'bg-info',
                                'subtitle' => 'Clientes activos',
                                'isIncome' => false,
                            ],
                            [
                                'title' => 'Clientes Nuevos Este Mes',
                                'icon' => 'ri-user-add-line',
                                'value' => $newClientsThisMonth,
                                'color' => 'bg-secondary',
                                'subtitle' => 'Nuevas membresías',
                                'isIncome' => false,
                            ],
                            [
                                'title' => 'Check-ins Hoy',
                                'icon' => 'ri-check-line',
                                'value' => $checkInsToday,
                                'color' => 'bg-dark',
                                'subtitle' => 'Entradas registradas',
                                'isIncome' => false,
                            ],
                            [
                                'title' => 'Total de Clientes',
                                'icon' => 'ri-group-line',
                                'value' => $totalClientes,
                                'color' => 'bg-primary',
                                'subtitle' => 'Clientes registrados',
                                'isIncome' => false,
                            ],
                        ];
                    @endphp
                    @foreach ($summaryCards as $card)
                        <div class="col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-animate {{ $card['color'] }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-white mb-0">{{ $card['title'] }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white">
                                                @if ($card['isIncome'])
                                                    Bs. {{ $card['value'] }}
                                                @else
                                                    {{ $card['value'] }}
                                                @endif
                                            </h4>
                                            <span
                                                class="text-decoration-underline text-white-50">{{ $card['subtitle'] }}</span>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-white bg-opacity-25 rounded fs-3 material-shadow">
                                                <i class="{{ $card['icon'] }} text-white"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    @endforeach
                </div>
                <div class="row mt-4">
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Ingresos en los Últimos 30 Días</h6>
                            </div>
                            <div class="card-body">
                                <div id="incomeChart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Servicios Más Solicitados</h6>
                            </div>
                            <div class="card-body">
                                <div id="servicesChart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Asistencias de Esta Semana</h6>
                            </div>
                            <div class="card-body">
                                <div id="attendanceChart" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Membresías Más Adquiridas</h6>
                            </div>
                            <div class="card-body">
                                <div id="membershipsChart" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">ingresos</h6>
                            </div>
                            <div class="card-body">
                                <div id="incomeInteractiveChart" style="height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="generatePDF" class="btn btn-danger">Descargar PDF</button>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var incomeChart = echarts.init(document.getElementById('incomeChart'));
        var incomeOption = {
            tooltip: {
                trigger: 'axis',
                formatter: function(params) {
                    return params[0].name + '<br/>' +
                        params[0].seriesName + ': Bs. ' + params[0].value.toFixed(2);
                }
            },
            xAxis: {
                type: 'category',
                data: {!! json_encode($incomeDates) !!},
                axisLabel: {
                    rotate: 45,
                    interval: 'auto'
                }
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: function(value) {
                        return 'Bs. ' + value.toFixed(2);
                    }
                }
            },
            series: [{
                name: 'Ingresos',
                data: {!! json_encode($incomeValues) !!},
                type: 'line',
                smooth: true,
                lineStyle: {
                    color: '#5470C6',
                    width: 4
                },
                itemStyle: {
                    color: '#5470C6'
                },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgba(84,112,198,0.5)'
                    }, {
                        offset: 1,
                        color: 'rgba(84,112,198,0.1)'
                    }])
                }
            }]
        };
        incomeChart.setOption(incomeOption);
        var servicesChart = echarts.init(document.getElementById('servicesChart'));
        var servicesOption = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 10,
                data: {!! json_encode($popularServices->pluck('nombre')) !!}
            },
            series: [{
                name: 'Servicios',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: {!! json_encode(
                    $popularServices->map(function ($service) {
                        return ['value' => $service->total, 'name' => $service->nombre];
                    }),
                ) !!}
            }]
        };
        servicesChart.setOption(servicesOption);
        var attendanceChart = echarts.init(document.getElementById('attendanceChart'));
        var attendanceOption = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            xAxis: {
                type: 'category',
                data: {!! json_encode($attendanceThisWeek->pluck('date')) !!},
                axisLabel: {
                    rotate: 45,
                    interval: 0
                }
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: 'Asistencias',
                data: {!! json_encode($attendanceThisWeek->pluck('total')) !!},
                type: 'bar',
                itemStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: '#83bff6'
                    }, {
                        offset: 0.5,
                        color: '#188df0'
                    }, {
                        offset: 1,
                        color: '#188df0'
                    }])
                },
                emphasis: {
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: '#2378f7'
                        }, {
                            offset: 0.7,
                            color: '#2378f7'
                        }, {
                            offset: 1,
                            color: '#83bff6'
                        }])
                    }
                }
            }]
        };
        attendanceChart.setOption(attendanceOption);
        var membershipsChart = echarts.init(document.getElementById('membershipsChart'));
        var membershipsOption = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 10,
                data: {!! json_encode($popularMemberships->pluck('nombre')) !!}
            },
            series: [{
                name: 'Membresías',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: {!! json_encode(
                    $popularMemberships->map(function ($membership) {
                        return ['value' => $membership->total, 'name' => $membership->nombre];
                    }),
                ) !!},
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        };
        membershipsChart.setOption(membershipsOption);
        window.addEventListener('resize', function() {
            incomeChart.resize();
            servicesChart.resize();
            attendanceChart.resize();
            membershipsChart.resize();
        });


        let incomeData = {
            today: {
                services: {{ $incomeTodayServices }},
                memberships: {{ $incomeTodayMemberships }}
            },
            week: {
                services: {{ $incomeThisWeekServices }},
                memberships: {{ $incomeThisWeekMemberships }}
            },
            month: {
                services: {{ $incomeThisMonthServices }},
                memberships: {{ $incomeThisMonthMemberships }}
            },
            year: {
                services: {{ $incomeThisYearServices }},
                memberships: {{ $incomeThisYearMemberships }}
            }
        };

        var incomeInteractiveChart = echarts.init(document.getElementById('incomeInteractiveChart'));

        // Configuración inicial del gráfico de barras
        let currentFilter = 'today';

        function getChartOption(filter) {
            return {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                legend: {
                    data: ['Servicios', 'Membresías'],
                    top: 10
                },
                xAxis: {
                    type: 'category',
                    data: ['Ingresos']
                },
                yAxis: {
                    type: 'value',
                    axisLabel: {
                        formatter: function(value) {
                            return 'Bs. ' + value.toFixed(2);
                        }
                    }
                },
                series: [{
                        name: 'Servicios',
                        type: 'bar',
                        data: [incomeData[filter].services],
                        itemStyle: {
                            color: '#5470C6'
                        }
                    },
                    {
                        name: 'Membresías',
                        type: 'bar',
                        data: [incomeData[filter].memberships],
                        itemStyle: {
                            color: '#91CC75'
                        }
                    }
                ]
            };
        }

        // Inicializa el gráfico con el filtro "Hoy"
        incomeInteractiveChart.setOption(getChartOption(currentFilter));

        // Función para cambiar de filtro dinámicamente
        function updateChart(filter) {
            currentFilter = filter;
            incomeInteractiveChart.setOption(getChartOption(filter));
        }

        // Generar los botones de filtro en HTML
        document.getElementById('incomeInteractiveChart').insertAdjacentHTML('beforebegin', `
        <div class="mb-4">
            <button onclick="updateChart('today')" class="btn btn-sm btn-primary">Hoy</button>
            <button onclick="updateChart('week')" class="btn btn-sm btn-secondary">Esta Semana</button>
            <button onclick="updateChart('month')" class="btn btn-sm btn-warning">Este Mes</button>
            <button onclick="updateChart('year')" class="btn btn-sm btn-success">Este Año</button>
        </div>
    `);

        // Ajuste de tamaño al redimensionar la ventana
        window.addEventListener('resize', function() {
            incomeInteractiveChart.resize();
        });



        document.getElementById('generatePDF').addEventListener('click', async function() {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4'); // Orientación vertical, unidad mm, tamaño A4

            const charts = [{
                    id: 'incomeChart',
                    title: 'Ingresos en los Últimos 30 Días'
                },
                {
                    id: 'servicesChart',
                    title: 'Servicios Más Solicitados'
                },
                {
                    id: 'attendanceChart',
                    title: 'Asistencias de Esta Semana'
                },
                {
                    id: 'membershipsChart',
                    title: 'Membresías Más Adquiridas'
                },
                {
                    id: 'incomeInteractiveChart',
                    title: 'Ingresos Detallados'
                }
            ];

            const margin = 10; // Margen del PDF
            const pageHeight = pdf.internal.pageSize.height; // Altura de la página
            const pageWidth = pdf.internal.pageSize.width; // Ancho de la página
            let yPosition = margin; // Posición inicial para contenido

            const header = (title) => {
                pdf.setFont('helvetica', 'bold');
                pdf.setFontSize(16);
                pdf.text(title, pageWidth / 2, margin, {
                    align: 'center'
                });
                pdf.setFontSize(12);
                pdf.setTextColor(100);
                pdf.text(`Fecha: ${new Date().toLocaleDateString()}`, pageWidth - margin, margin + 5, {
                    align: 'right'
                });
                pdf.setTextColor(0);
                yPosition += 15; // Espacio para el encabezado
            };

            // Añadir encabezado inicial
            header('Reporte de Gráficas');

            for (let chart of charts) {
                const chartElement = document.getElementById(chart.id);
                if (chartElement) {
                    // Capturar la gráfica como imagen
                    const canvas = await html2canvas(chartElement);
                    const imageData = canvas.toDataURL('image/png');
                    const imgWidth = pageWidth - margin * 2; // Ancho de la imagen considerando márgenes
                    const imgHeight = (canvas.height * imgWidth) / canvas.width; // Mantener proporción

                    // Si no hay suficiente espacio para una gráfica completa, añadir nueva página
                    if (yPosition + imgHeight + 10 > pageHeight) {
                        pdf.addPage();
                        yPosition = margin;
                        header('Continuación del Reporte');
                    }

                    // Añadir título de la gráfica
                    pdf.setFont('helvetica', 'bold');
                    pdf.setFontSize(14);
                    pdf.text(chart.title, margin, yPosition);
                    yPosition += 8; // Espacio debajo del título

                    // Añadir la gráfica al PDF
                    pdf.addImage(imageData, 'PNG', margin, yPosition, imgWidth, imgHeight);
                    yPosition += imgHeight + 10; // Espacio debajo de la gráfica
                }
            }

            // Pie de página
            const totalPages = pdf.internal.getNumberOfPages();
            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                pdf.setFontSize(10);
                pdf.text(
                    `Página ${i} de ${totalPages}`,
                    pageWidth / 2,
                    pageHeight - margin, {
                        align: 'center'
                    }
                );
            }

            // Guardar el PDF
            pdf.save('Reporte_Graficas.pdf');
        });
    </script>
@endpush
