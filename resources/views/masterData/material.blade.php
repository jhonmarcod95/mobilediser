@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Material Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Material Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Material List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/materials/add') }}"><i class="fa fa-user-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%; white-space: nowrap">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Material Code</th>
                                    <th>Description</th>
                                    <th>Base unit</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($materials as $material)
                                    <tr>
                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/materials/edit?id=' . $material->id) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                        </td>
                                        <td>{{ $material->material_code }}</td>
                                        <td>{{ $material->material_description }}</td>
                                        <td>{{ $material->base_unit }}</td>
                                        <td>{{ $material->created_at }}</td>
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

