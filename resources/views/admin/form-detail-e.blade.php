@extends('layouts/contentLayoutMaster')

@section('title', 'Fir Detail')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
@endsection

@section('content')
    <section class="app-user-view-connections">
        <div class="row">
        @include('admin.partials.stats')
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                @include('admin.partials.sidebar')
                <!-- /User Card -->

            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
            @include('admin.partials.pill', ['active_link' => 4])
            <!--/ User Pills -->

                <!-- connection -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-50">Scruitny by Legal Branch</h4>
                        {{--                        <p class="mb-0">Change to notification settings, the user will get the update</p>--}}
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap text-center border-bottom">
                            <thead>
                            <tr>
                                <th class="text-center">S.No.</th>
                                <th class="text-start">Heads</th>
                                <th class="text-start">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($form_e as $index => $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $row['head'] }}</td>
                                    <td class="text-start">
                                        @foreach($row['options'] as $key => $value)
                                            @if(!empty($feedback))
                                                @if($key === 0 && $feedback->{$row['field_name']} == '0')
                                                    {{ $value }}
                                                @endif

                                                @if($key === 1 && $feedback->{$row['field_name']} == '1')
                                                    {{ $value }}
                                                @endif

                                                @if($key === 2 && $feedback->{$row['field_name']} == '2')
                                                    {{ $value }}
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>{{ count($row['options']) + 1 }}</td>
                                <td class="text-start">Recommended Disposal of Scrutiny</td>
                                <td>
                                    {{ !empty($feedback) ? $feedback->rec_dis_scruit : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ count($row['options']) + 2 }}</td>
                                <td class="text-start">LACUNAS</td>
                                <td>
                                    {{ !empty($feedback) ? $feedback->lacunas : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ count($row['options']) + 3 }}</td>
                                <td class="text-start">PDSP Opinion</td>
                                <td>
                                    {{ !empty($feedback) ? $feedback->pdsp_opinion : '' }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!--/ connection -->
            </div>
            <!--/ User Content -->
        </div>
    </section>

@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script>
        var goalOverviewChartOptions1 = {
            chart: {
                height: 180,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                }
            },
            colors: ["#51e5a8"],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '77%'
                    },
                    track: {
                        background: "#ebe9f1",
                        strokeWidth: '50%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            color: "#5e5873",
                            fontSize: '2.86rem',
                            fontWeight: '600'
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: [window.colors.solid.success],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            series: [{{ $stat_b['avg_b'] }}],
            stroke: {
                lineCap: 'round'
            },
            grid: {
                padding: {
                    bottom: 30
                }
            }
        };
        var chart1 = new ApexCharts(document.querySelector("#goal-overview-radial-bar-chart-1"), goalOverviewChartOptions1);
        chart1.render();
        var goalOverviewChartOptions2 = {
            chart: {
                height: 180,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                }
            },
            colors: ["#51e5a8"],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '77%'
                    },
                    track: {
                        background: "#ebe9f1",
                        strokeWidth: '50%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            color: "#5e5873",
                            fontSize: '2.86rem',
                            fontWeight: '600'
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: [window.colors.solid.success],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            series: [{{ $stat_c['avg_c'] }}],
            stroke: {
                lineCap: 'round'
            },
            grid: {
                padding: {
                    bottom: 30
                }
            }
        };
        var chart2 = new ApexCharts(document.querySelector("#goal-overview-radial-bar-chart-2"), goalOverviewChartOptions2);
        chart2.render();
        var goalOverviewChartOptions3 = {
            chart: {
                height: 180,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                }
            },
            colors: ["#51e5a8"],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '77%'
                    },
                    track: {
                        background: "#ebe9f1",
                        strokeWidth: '50%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            color: "#5e5873",
                            fontSize: '2.86rem',
                            fontWeight: '600'
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: [window.colors.solid.success],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            series: [{{ $stat_d['avg_d'] }}],
            stroke: {
                lineCap: 'round'
            },
            grid: {
                padding: {
                    bottom: 30
                }
            }
        };
        var chart3 = new ApexCharts(document.querySelector("#goal-overview-radial-bar-chart-3"), goalOverviewChartOptions3);
        chart3.render();
        var goalOverviewChartOptions4 = {
            chart: {
                height: 180,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                }
            },
            colors: ["#51e5a8"],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '77%'
                    },
                    track: {
                        background: "#ebe9f1",
                        strokeWidth: '50%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            color: "#5e5873",
                            fontSize: '2.86rem',
                            fontWeight: '600'
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: [window.colors.solid.success],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            series: [{{ $stat_e['avg_e'] }}],
            stroke: {
                lineCap: 'round'
            },
            grid: {
                padding: {
                    bottom: 30
                }
            }
        };
        var chart4 = new ApexCharts(document.querySelector("#goal-overview-radial-bar-chart-4"), goalOverviewChartOptions4);
        chart4.render();
        $('.remarks').on('focusout', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.fir.form-detail-save", $fir->fir_id) }}',
                data: {
                    table: 'facts_scrutiny_legal_branch',
                    column: $(this).data('column'),
                    value: $(this).val(),
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {

                    if (response.status == 'success') {
                        toastr['success'](response.message, 'Success!', {
                            closeButton: true,
                            tapToDismiss: false
                        });
                    } else {
                        toastr['error'](response.message, 'Error!', {
                            closeButton: true,
                            tapToDismiss: false
                        });
                    }
                },
                error: function (response) {
                    toastr['error'](response.message, 'Error!', {
                        closeButton: true,
                        tapToDismiss: false
                    });
                }
            });
        })

        function saveData(table, column, value) {
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.fir.form-detail-save", $fir->fir_id) }}',
                data: {
                    table,
                    column,
                    value,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {

                    if (response.status == 'success') {
                        toastr['success'](response.message, 'Success!', {
                            closeButton: true,
                            tapToDismiss: false
                        });
                    } else {
                        toastr['error'](response.message, 'Error!', {
                            closeButton: true,
                            tapToDismiss: false
                        });
                    }
                },
                error: function (response) {
                    toastr['error'](response.message, 'Error!', {
                        closeButton: true,
                        tapToDismiss: false
                    });
                }
            });
        }
    </script>
@endsection

