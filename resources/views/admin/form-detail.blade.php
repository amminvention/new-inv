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
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <section class="app-user-view-connections">
        <div class="row">

            <!-- User Content -->
            @if ($type == 1)
                <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                    <!-- User Pills -->
                @include('admin.partials.pill-detail', ['active_link' => 1])
                <!--/ User Pills -->

                    <!-- connection -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-50">Quality of Investigation</h4>
                            {{--                        <p class="mb-0">Change to notification settings, the user will get the update</p>--}}
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap text-center border-bottom">
                                <thead>
                                <tr>
                                    <th class="text-center">S.No.</th>
                                    <th class="text-start">Heads</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($form_b as $index => $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $row['head'] }}</td>
                                        <td>
                                            <div class="">
                                                @foreach($row['options'] as $key => $value)
                                                    <div class="form-check form-check-inline @if($key == 0) form-check-danger @elseif($key == 1) form-check-success @else form-check-secondary  @endif">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="{{ $row['field_name'] }}"
                                                            id="inlineRadio{{ $index . $key }}"
                                                            value="{{ $key }}"
                                                            onclick="saveData('facts_quality', '{{ $row['field_name'] }}', {{ $key }})"
                                                            @if(!empty($feedback))
                                                            @if($key === 0 && $feedback->{$row['field_name']} == '0')
                                                            checked
                                                            @endif

                                                            @if($key === 1 && $feedback->{$row['field_name']} == '1')
                                                            checked
                                                            @endif

                                                            @if($key === 2 && $feedback->{$row['field_name']} == '2')
                                                            checked
                                                            @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label"
                                                               for="inlineRadio{{ $index . $key }}">{{ $value }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!--/ connection -->
                </div>
            @elseif($type == 2)
                <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                    <!-- User Pills -->
                @include('admin.partials.pill-detail', ['active_link' => 2])
                <!--/ User Pills -->

                    <!-- connection -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-50">Quality of Evidence</h4>
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
                                @foreach($form_c as $index => $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $row['head'] }}</td>
                                        <td class="text-start">

                                            <div class="">
                                                @foreach($row['options'] as $key => $value)
                                                    <div class="form-check form-check-inline @if($key == 0) form-check-danger @elseif($key == 1) form-check-success @else form-check-secondary  @endif">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="{{ $row['field_name'] }}"
                                                            id="inlineRadio{{ $index . $key }}"
                                                            value="{{ $key }}"
                                                            onclick="saveData('evidence_quality', '{{ $row['field_name'] }}', {{ $key }})"
                                                            @if(!empty($feedback))
                                                            @if($key === 0 && $feedback->{$row['field_name']} == '0')
                                                            checked
                                                            @endif

                                                            @if($key === 1 && $feedback->{$row['field_name']} == '1')
                                                            checked
                                                            @endif

                                                            @if($key === 2 && $feedback->{$row['field_name']} == '2')
                                                            checked
                                                            @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label" for="inlineRadio{{ $index . $key }}">{{ $value }}</label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!--/ connection -->
                </div>
            @elseif($type == 3)
                <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                    <!-- User Pills -->
                @include('admin.partials.pill-detail', ['active_link' => 3])
                <!--/ User Pills -->

                    <!-- connection -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-50">Monitoring of Supervision</h4>
                            {{--                        <p class="mb-0">Change to notification settings, the user will get the update</p>--}}
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap text-center border-bottom">
                                <thead>
                                <tr>
                                    <th class="text-center">S.No.</th>
                                    <th class="text-start">Heads</th>
                                    <th class="text-start">SHO/IO</th>
                                    <th class="text-start">SDPO</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($form_d as $index => $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $row['head'] }}</td>
                                        <td class="text-start">

                                            <div class="">
                                                @foreach($row['options_1'] as $key => $value)
                                                    <div class="form-check form-check-inline @if($key == 0) form-check-danger @elseif($key == 1) form-check-success @else form-check-secondary  @endif">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="{{ $row['field_name_1'] }}"
                                                            id="inlineRadio{{ $index . $key }}"
                                                            value="{{ $key }}"
                                                            onclick="saveData('monitoring_supervision', '{{ $row['field_name_1'] }}', {{ $key }})"
                                                            @if(!empty($feedback))
                                                            @if($key === 0 && $feedback->{$row['field_name_1']} == '0')
                                                            checked
                                                            @endif

                                                            @if($key === 1 && $feedback->{$row['field_name_1']} == '1')
                                                            checked
                                                            @endif

                                                            @if($key === 2 && $feedback->{$row['field_name_1']} == '2')
                                                            checked
                                                            @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label" for="inlineRadio{{ $index . $key }}">{{ $value }}</label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </td>
                                        <td class="text-start">

                                            <div class="">
                                                @foreach($row['options_2'] as $key => $value)
                                                    <div class="form-check form-check-inline @if($key == 0) form-check-danger @elseif($key == 1) form-check-success @else form-check-secondary  @endif">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="{{ $row['field_name_2'] }}"
                                                            id="iinlineRadio{{ $index . $key }}"
                                                            value="{{ $key }}"
                                                            onclick="saveData('monitoring_supervision', '{{ $row['field_name_2'] }}', {{ $key }})"
                                                            @if(!empty($feedback))
                                                            @if($key === 0 && $feedback->{$row['field_name_2']} == '0')
                                                            checked
                                                            @endif

                                                            @if($key === 1 && $feedback->{$row['field_name_2']} == '1')
                                                            checked
                                                            @endif

                                                            @if($key === 2 && $feedback->{$row['field_name_2']} == '2')
                                                            checked
                                                            @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label" for="iinlineRadio{{ $index . $key }}">{{ $value }}</label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!--/ connection -->
                </div>
            @else
                <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                    <!-- User Pills -->
                @include('admin.partials.pill-detail', ['active_link' => 4])
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

                                            <div class="">
                                                @foreach($row['options'] as $key => $value)
                                                    <div class="form-check form-check-inline @if($key == 0) form-check-danger @elseif($key == 1) form-check-secondary @else form-check-success  @endif">
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            name="{{ $row['field_name'] }}"
                                                            id="iinlineRadio{{ $index . $key }}"
                                                            value="{{ $key }}"
                                                            onclick="saveData('facts_scrutiny_legal_branch', '{{ $row['field_name'] }}', {{ $key }})"
                                                            @if(!empty($feedback))
                                                            @if($key === 0 && $feedback->{$row['field_name']} == '0')
                                                            checked
                                                            @endif

                                                            @if($key === 1 && $feedback->{$row['field_name']} == '1')
                                                            checked
                                                            @endif

                                                            @if($key === 2 && $feedback->{$row['field_name']} == '2')
                                                            checked
                                                            @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label"
                                                               for="iinlineRadio{{ $index . $key }}">{{ $value }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>{{ count($row['options']) + 1 }}</td>
                                    <td class="text-start">Recommended Disposal of Scrutiny</td>
                                    <td>
                                        <textarea class="form-control remarks" data-column="rec_dis_scruit" name="" id="" cols="10" rows="1">{{ !empty($feedback) ? $feedback->rec_dis_scruit : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ count($row['options']) + 2 }}</td>
                                    <td class="text-start">LACUNAS</td>
                                    <td>
                                        <textarea class="form-control remarks" data-column="lacunas" name="" id="" cols="10" rows="1">{{ !empty($feedback) ? $feedback->lacunas : '' }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ count($row['options']) + 3 }}</td>
                                    <td class="text-start">PDSP Opinion</td>
                                    <td>
                                        <textarea class="form-control remarks" data-column="pdsp_opinion" name="" id="" cols="10" rows="1">{{ !empty($feedback) ? $feedback->pdsp_opinion : '' }}</textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!--/ connection -->
                </div>
            @endif
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
