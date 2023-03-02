@extends('layouts/contentLayoutMaster')

@section('title', 'FIR List')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
@endsection

@section('page-style')
    {{--    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice-list.css')}}">--}}
    <link rel="stylesheet" href="{{asset('css/base/pages/ui-feather.css')}}">
@endsection

@section('content')
    <section class="invoice-list-wrapper">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col-12">
                        <h5 class="card-title mb-3">Search Filter</h5>
                    </div>
                    <div class="col-12">
                        <form class="form form-vertical">
                            <div class="row">
                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="range">Range</label>
                                        <select name="range" id="range" class="form-control" onchange="getDistricts(this.value)">
                                            <option value="">Select Range</option>
                                            @foreach($range as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="district">District</label>
                                        <select name="district" id="district" class="form-control" onchange="getPoliceStations(this.value)">
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="police_station">Police Station</label>
                                        <select name="police_station" id="police_station" class="form-control">
                                            <option value="">Select Police Station</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="fir-no">FIR No.</label>
                                        <input
                                            type="text"
                                            id="fir-no"
                                            class="form-control"
                                            name="fir_no"
                                            placeholder="FIR No."
                                        />
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="fir-year">FIR Year</label>
                                        <input type="text" id="fir-year" class="form-control" name="fir_year" placeholder="FIR Year" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="fir-section">Section</label>
                                        <input type="text" id="fir-section" class="form-control" name="section" placeholder="Section" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-1">
                                        <label class="form-label" for="fir-io">Investigation Officer</label>
                                        <input type="text" id="fir-io" class="form-control" name="io" placeholder="Investigation Officer" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary me-1">Search</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="card-datatable table-responsive">
                <table class="list-table table">
                    <thead>
                    <tr>
                        <th class="text-center">S.No.</th>
                        <th class="text-center">Range</th>
                        <th class="text-center">District</th>
                        <th class="text-center">Police Station</th>
                        <th class="text-center">Fir No.</th>
                        <th class="text-center">Fir Year</th>
                        <th class="text-center">Sections</th>
                        <th class="text-center">I/O</th>
                        <th width="15%" class="text-center">ACTION</th>
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
    <script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
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
                searching: false,
                ajax: {
                    "url": '{!! route('admin.fir.index') !!}',
                    "data": function (d) {
                        d.range = $('#range').val();
                        d.district = $('#district').val();
                        d.police_station = $('#police_station').val();
                        d.fir_no = $('#fir-no').val();
                        d.fir_year = $('#fir-year').val();
                        d.sections = $('#fir-section').val();
                        d.io = $('#fir-io').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'range', name: 'range'},
                    {data: 'district', name: 'district'},
                    {data: 'police_station', name: 'police_station'},
                    {data: 'fir_no', name: 'fir_no'},
                    {data: 'fir_year', name: 'fir_year'},
                    {data: 'sections', name: 'sections'},
                    {data: 'officer', name: 'officer'},
                    {
                        data: 'action', name: 'action'
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
