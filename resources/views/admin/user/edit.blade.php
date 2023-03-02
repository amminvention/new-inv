@extends('layouts/contentLayoutMaster')

@section('title', 'Edit User')

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
                        <h4 class="card-title">Edit User</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            {!! implode('', $errors->all('<div>:message</div>')) !!}
                        @endif
                        <form class="form form-horizontal" method="POST" action="{{ route('admin.user.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="text" id="first-name" value="{{ $user->name }}" class="form-control" name="name" placeholder="Name" />
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="email-id">Email</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="email" id="email-id" value="{{ $user->email }}" class="form-control" name="email" placeholder="Email" />
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Password</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Password" />
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="cnic">CNIC</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="text" id="cnic" value="{{ $user->cnic }}" class="form-control" name="cnic" placeholder="CNIC" />
                                            @error('cnic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Mobile</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input required type="text" id="contact-info" class="form-control" value="{{ $user->mobile }}" name="mobile" placeholder="Mobile" />
                                            @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Range</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="range_id" id="range" class="form-control" onchange="getDistricts(this.value)">
                                                <option value="">Select Range</option>
                                                @foreach($range as $key => $value)
                                                    <option value="{{ $key }}" {{ $user->range_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('range_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">District</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="district_id" id="district" class="form-control" onchange="getPoliceStations(this.value)">
                                                <option value="">Select District</option>
                                            </select>
                                            @error('district_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="contact-info">Police Station</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="police_station_id" id="police_station" class="form-control">
                                                <option value="">Select Police Station</option>
                                            </select>
                                            @error('police_station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
