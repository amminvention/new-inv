@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection

@section('page-style')
    {{--    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice-list.css')}}">--}}
    <link rel="stylesheet" href="{{asset('css/base/pages/ui-feather.css')}}">
@endsection

@section('content')
    <section class="invoice-list-wrapper">
        <div class="card">
{{--            <div class="card-header border-bottom">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12">--}}
{{--                        <h5 class="card-title mb-3">Search Filter</h5>--}}
{{--                    </div>--}}
{{--                    <div class="col-12">--}}
{{--                        <form class="form form-vertical">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="range">Range</label>--}}
{{--                                        <select name="range" id="range" class="form-control" onchange="getDistricts(this.value)">--}}
{{--                                            <option value="">Select Range</option>--}}
{{--                                            @foreach($range as $key => $value)--}}
{{--                                                <option value="{{ $key }}">{{ $value }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="district">District</label>--}}
{{--                                        <select name="district" id="district" class="form-control" onchange="getPoliceStations(this.value)">--}}
{{--                                            <option value="">Select District</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="police_station">Police Station</label>--}}
{{--                                        <select name="police_station" id="police_station" class="form-control">--}}
{{--                                            <option value="">Select Police Station</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="fir-no">FIR No.</label>--}}
{{--                                        <input--}}
{{--                                            type="text"--}}
{{--                                            id="fir-no"--}}
{{--                                            class="form-control"--}}
{{--                                            name="fir_no"--}}
{{--                                            placeholder="FIR No."--}}
{{--                                        />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="fir-year">FIR Year</label>--}}
{{--                                        <input type="text" id="fir-year" class="form-control" name="fir_year" placeholder="FIR Year" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="fir-section">Section</label>--}}
{{--                                        <input type="text" id="fir-section" class="form-control" name="section" placeholder="Section" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-3">--}}
{{--                                    <div class="mb-1">--}}
{{--                                        <label class="form-label" for="fir-io">Investigation Officer</label>--}}
{{--                                        <input type="text" id="fir-io" class="form-control" name="io" placeholder="Investigation Officer" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-3">--}}
{{--                                    <button type="submit" class="btn btn-primary me-1">Search</button>--}}
{{--                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}


{{--            </div>--}}
            <div class="card-datatable table-responsive">
                <table class="list-table table">
                    <thead>
                    <tr>
                        <th class="text-center">S.No.</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">CNIC</th>
                        <th class="text-center">Mobile</th>
                        <th class="text-center">Range</th>
                        <th class="text-center">District</th>
                        <th class="text-center">Police Station</th>
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
@endsection

@section('page-script')
    {{--    <script src="{{asset('js/scripts/pages/app-invoice-list.js')}}"></script>--}}
    <script src="{{asset('js/scripts/ui/ui-feather.js')}}"></script>
    <script>
        $(document).ready(function () {
            var table = $('.list-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    "url": '{!! route('admin.user.index') !!}',
                    // "data": function (d) {
                    //     d.range = $('#range').val();
                    //     d.district = $('#district').val();
                    //     d.police_station = $('#police_station').val();
                    // }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'cnic', name: 'cnic'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'range', name: 'range'},
                    {data: 'district', name: 'district'},
                    {data: 'police_station', name: 'police_station'},
                    {
                        data: 'action', name: 'action'
                    }
                ],
                order: [[1, 'desc']],
                dom:
                    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: 'Show _MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                // Buttons with Dropdown
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        text: feather.icons['external-link'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                        buttons: [
                            {
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + 'Print',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            },
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            },
                            {
                                extend: 'pdf',
                                text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + 'Pdf',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 5] }
                            }
                        ],
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                            $(node).parent().removeClass('btn-group');
                            setTimeout(function () {
                                $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex mt-50');
                            }, 50);
                        }
                    },
                    {
                        text: 'Add New User',
                        className: 'add-new btn btn-primary',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        },
                        action: function (e, dt, node, config) {
                            // Redirect to the desired URL
                            window.location.href = '{{ route("admin.user.create") }}';
                        }
                    }
                ],
                "drawCallback": function (settings) {
                    feather.replace()
                }
            });
            // $('.select2').select2();
            $('.form-vertical').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            })
        })

        function getDistricts(id) {
            $.ajax({
                type:'POST',
                url:'/get-districts',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'id': id
                },
                success:function(data) {
                    $("#district").html(data);
                }
            });
        }

        function getPoliceStations(id) {
            $.ajax({
                type:'POST',
                url:'/get-police-stations',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'id': id
                },
                success:function(data) {
                    $("#police_station").html(data);
                }
            });
        }
    </script>
@endsection
