{{--
    form inputs has @ to catch error of null values
--}}

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if($isEdit)
                User Profile
            @else
                User Registration
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/users') }}"><i class="fa fa-user"></i>User Master Data</a></li>
            <li class="active">
                @if($isEdit)
                    Profile
                @else
                    Registration
                @endif
            </li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['url' => '/users/' . $actionUrl, 'method' => 'POST', 'target' => '_top', 'files' => true]) !!}
                        {!! @Form::hidden('merchandiser_id', $user->merchandiser_id) !!}

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="image">
                                        <img class="img-circle" id="imgSrc" src="@if($isEdit){{ asset('storage/' . $user->userimage->image_path) }}@else {{ asset('storage/avatars/avatar.png') }} @endif"
                                             alt="{{ asset('storage/avatars/avatar.png') }}"
                                             height="200px" width="200px"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file">
                                            <span>Choose Image</span>
                                            {!! Form::file('img', ['id' => 'img']) !!}
                                        </span>
                                        <span class="fileinput-filename"></span><span class="fileinput-new"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Last Name</label>
                                    {!! @Form::text('last_name', $user->last_name, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">First Name</label>
                                    {!! @Form::text('first_name', $user->first_name, ['class' => 'form-control', 'maxlength' => '255', 'required']) !!}
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Gender</label>
                                    {!! @Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], $user->gender, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Contact#</label>
                                    {!! @Form::text('contact_number', $user->contact_number, ['class' => 'form-control', 'maxlength' => '11', '']) !!}
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Agency</label>
                                    {!! @Form::select('agency', $agency, $user->agency_code, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                </div>

                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Account Type</label>
                                    {!! @Form::select('accountType', $accountType, $user->account_type, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Address</label>
                                    {!! @Form::textarea('address', $user->address, ['class' => 'form-control', 'maxlength' => '255', 'rows' => '5', 'required']) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Birthday</label>
                                    {!! @Form::date('birthday', $user->birth_date, ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Username</label>
                                    {!! @Form::text('username', $user->username, ['class' => 'form-control', 'maxlength' => '30', 'required']) !!}
                                </div>

                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-muted">Email</label>
                                    {!! @Form::email('email', $user->email, ['class' => 'form-control', 'maxlength' => '30', '']) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-muted">Password</label>
                                    {!! @Form::password('password', ['class' => 'form-control', 'maxlength' => '30']) !!}
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                @include('layouts.errors')
                            </div>

                        </div>

                        <div class="box-footer">
                            @if($isEdit)
                                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}
                            @else
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imgSrc').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#img").change(function () {
            readURL(this);
        });
    </script>



@endsection


