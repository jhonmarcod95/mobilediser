@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Customer Carried Materials
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-database"></i> Customer Carried Materials</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header ">
                        <h3 class="box-title">Customer Carried Material List</h3>

                        <div class="row">
                            {!! Form::open(['url' => '/customers/carried', 'method' => 'GET']) !!}
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <br>
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>&nbsp; Search</button>
                                </div>

                                <div class="pull-right">&nbsp;</div>

                                <div class="pull-right">
                                    <span>Material: </span> <br>
                                    {!! Form::select('material_ids[]', $search_materials, null, ['class' => 'form-control select2', 'multiple', 'required']) !!}
                                </div>

                                <div class="pull-right">&nbsp;</div>

                                <div class="pull-right">
                                    <span>Customer: </span> <br>
                                    {!! Form::select('customer_ids[]', $search_customers, null, ['class' => 'form-control select2', 'multiple', 'required']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="table table-responsive">
                                <table id="dataTable2" class="table table-bordered" style="white-space: nowrap; width: 1%">
                                    <thead>
                                    <th>Code</th>
                                    <th>Customer</th>
                                    @foreach($materials as $material)
                                        <th>{{ $material->material_description }}</th>
                                    @endforeach
                                    </thead>
                                    <tbody>
                                    @foreach($carried_materials->unique('customer_code') as $customer)
                                        <tr>
                                            <td>{{ $customer->customer_code }}</td>
                                            <td>{{ $customer->name }}</td>
                                            @foreach($materials as $material)
                                                <td align="center">
                                                @if(count($carried_materials->where('customer_code', $customer->customer_code)
                                                                           ->where('material_code', $material->material_code)))
                                                    <input type="checkbox" checked>
                                                @else
                                                    <input type="checkbox">
                                                @endif
                                                </td>
                                            @endforeach

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                @include('layouts.errors')
            </div>
        </div>

    </section>
@endsection
