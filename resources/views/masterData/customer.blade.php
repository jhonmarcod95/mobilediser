@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-database"></i>Customer Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Customer List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/customers/add') }}"><i class="fa fa-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%; white-space: nowrap">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer Code</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Chain Code</th>
                                    <th>Chain Description</th>
                                    <th>Municipality Code</th>
                                    <th>Municipality Description</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/customers/edit?id=' . $customer->customer_id) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                        </td>
                                        <td>{{ $customer->customer_code }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->chain_code }}</td>
                                        <td>{{ $customer->type_description }}</td>
                                        <td>{{ $customer->municipality_code }}</td>
                                        <td>{{ $customer->municipality_description }}</td>
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

