@extends('layouts/contentLayoutMaster')

@section('title', 'Add User')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection

@section('page-style')
    {{--    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice-list.css')}}">--}}
    <link rel="stylesheet" href="{{asset('css/base/pages/ui-feather.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')
    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add User</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" method="POST" action="{{ url('/user-management/add-user') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="text" id="first-name" value="{{ old('name') }}" class="form-control" name="name" placeholder="Name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="email-id">Email</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="email" id="email-id" value="{{ old('email') }}" class="form-control" name="email" placeholder="Email" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Password</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required min="8" type="password" id="password" class="form-control" name="password" placeholder="Password" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="cnic">CNIC</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="text" id="cnic" value="{{ old('cnic') }}" class="form-control" name="cnic" placeholder="CNIC" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Mobile</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="text" id="contact-info" class="form-control" value="{{ old('mobile') }}" name="mobile" placeholder="Mobile" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Range</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="range" id="range" class="form-control" onchange="getDistricts(this.value)">
                                                <option value="">Select Range</option>
                                                @foreach($range as $key => $value)
                                                    <option value="{{ $key }}" {{ old('range') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">District</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="district" id="district" class="form-control" onchange="getPoliceStations(this.value)">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Police Station</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="police_station" id="police_station" class="form-control">
                                                <option value="">Select Police Station</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
            </div>
        </div>
    </section>
    <!-- Basic Horizontal form layout section end -->
@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
    <script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{--    <script src="{{asset('js/scripts/pages/app-invoice-list.js')}}"></script>--}}
    <script src="{{asset('js/scripts/ui/ui-feather.js')}}"></script>
    <script>

        $(document).ready(function () {
            @if(session('success'))
                toastr['success']('{{ session('success') }}', 'Success!', {
                    closeButton: true,
                    tapToDismiss: false
                });
            @endif
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
