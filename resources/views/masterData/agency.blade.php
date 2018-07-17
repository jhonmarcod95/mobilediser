@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Agency Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Agency Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Agency List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/agencies/add') }}"><i class="fa fa-user-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%; white-space: nowrap">
                                <thead>
                                <tr>
                                    <th></th>
                                    {{--<th>Agency Code</th>--}}
                                    <th>Agency</th>
                                    <th>Address</th>
                                    <th>Contact#</th>
                                    <th>Contact Person</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($agencies as $agency)
                                    <tr>

                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/agencies/edit?id=' . $agency->agency_code) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                        </td>
                                        {{--<td>{{ $agency->agency_code }}</td>--}}
                                        <td>{{ $agency->name }}</td>
                                        <td>{{ $agency->address }}</td>
                                        <td>{{ $agency->contact_number }}</td>
                                        <td>{{ $agency->contact_person }}</td>
                                    </tr>
                                @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

