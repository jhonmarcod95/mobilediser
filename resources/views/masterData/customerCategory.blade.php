@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Customer Account
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-database"></i>Customer Category Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Customer Account List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/customers/categories/add') }}"><i class="fa fa-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%; white-space: nowrap">
                                <thead>
                                <tr>
                                    <th>Account Code</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($customerCategories as $customerCategory)
                                    <tr>
                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/customers/categories/edit?id=' . $customerCategory->id) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                            &nbsp;
                                            {{ $customerCategory->category_code }}</td>
                                        <td>{{ $customerCategory->description }}</td>
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

