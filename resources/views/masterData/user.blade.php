@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>User Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">User List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/users/register') }}"><i class="fa fa-user-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 1%; white-space: nowrap">
                            <thead>
                            <tr>
                                <th></th>
                                {{--<th>User ID</th>--}}
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact#</th>
                                <th>Agency</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="btn-group ">
                                        <a href="{{ url('/users/register?id=' . $user->merchandiser_id) }}" ><li class="fa fa-pencil"></li></a>

                                    </div>
                                </td>
                                {{--<td>{{ $user->merchandiser_id }}</td>--}}
                                <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                <td>{{ $user->address }}</td>
                                <td>{{ $user->contact_number }}</td>
                                <td>{{ $user->agency_name }}</td>
                                <td>{{ $user->account_type }}</td>
                                <td><small class="label @if($user->account_status == 'ACTIVE'){{ 'label-success' }} @else {{ 'label-danger' }} @endif">{{ $user->account_status }}</small></td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('masterData.userModal')
    </section>
@endsection

