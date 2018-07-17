@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Municipality Master Data
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-user"></i>Municipality Master Data</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">Municipality List</h3>

                        {{-- Register --}}
                        <a class="btn btn-primary pull-right" href="{{ url('/municipalities/add') }}"><i class="fa fa-user-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">
                            <table id="dataTable" class="table table-hover table-striped" style="width: 100%; white-space: nowrap">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Municipality Code</th>
                                    <th>Municipality Description</th>
                                    <th>Province Description</th>
                                    <th>Region</th>
                                    <th>Island Group</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($municipalities as $municipality)
                                    <tr>
                                        <td>
                                            <div class="btn-group ">
                                                <a href="{{ url('/municipalities/edit?id=' . $municipality->id) }}" ><li class="fa fa-pencil"></li></a>
                                            </div>
                                        </td>
                                        <td>{{ $municipality->municipality_code }}</td>
                                        <td>{{ $municipality->municipality_description }}</td>
                                        <td>{{ $municipality->province_description}}</td>
                                        <td>{{ $municipality->region_description }}</td>
                                        <td>{{ $municipality->island_group_description }}</td>
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

