@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer Type Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Customer Type Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Customer Type List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/customers/types/add') }}"><i class="fa fa-user-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%; white-space: nowrap">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Type Code</th>
                                    <th>Description</th>
                                    <th>Category Code</th>
                                    <th>Category Description</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($customerTypes as $customerType)
                                    <tr>
                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/customers/types/edit?id=' . $customerType->id) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                        </td>
                                        <td>{{ $customerType->type_code }}</td>
                                        <td>{{ $customerType->description }}</td>
                                        <td>{{ $customerType->category_code }}</td>
                                        <td>{{ $customerType->category_description }}</td>
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

