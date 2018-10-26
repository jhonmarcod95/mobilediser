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
                            {!! Form::open(['id' => 'formFilter', 'url' => '/customer-carried', 'method' => 'GET']) !!}
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <br>
                                    <button class="btn btn-primary" type="button" onclick="retrieveCustomerCarried()"><i class="fa fa-search"></i>&nbsp; Search</button>
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
                            <div class="table table-responsive" id="tableCustomerCarried">

                            </div>
                        </div>
                    </div>

                    <div class="overlay" hidden>
                        <i class="fa fa-refresh fa-spin"></i>
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

@section('script')
    <script>
        function retrieveCustomerCarried(){
            $('.overlay').show();

            var query = $('#formFilter').serialize();
            $.ajax({
                type:'GET',
                url:'/customer-carried-data?' + query ,
                success: function(data){

                    //table columns
                    var headers = '';
                    $.each(data['materials'], function(key, val) {
                        headers += '<th>' + val.material_description + '</th>';
                    });

                    //table contents
                    var body = '';
                    $.each(data['carrieds'], function(key, val) {

                        //carried material checkbox tagging
                        var tag = '';
                        $.each(val.carrieds, function (key, mat) {
                            tag += '<td align=\'center\'> <input type=\'checkbox\' onchange="setCarried(\'' + val.customer_code + '\',\'' + mat.material_code  + '\')" value=\'' + mat.material_code + '\' ' + mat.tag + ' >';
                        });

                        body +=
                            '<tr>' +
                                '<td>' + val.customer_code +
                                '<td>' + val.name +
                                tag +
                            '</tr>';
                    });

                    var table =
                        '<table id=\'dataTable2\' class=\'table table-bordered\' style=\'white-space: nowrap; width: 1%\'>' +
                            '<thead>' +
                                '<th>Code</th>' +
                                '<th>Customer</th>' +
                                headers +
                            '</thead>' +
                            '<tbody>' +
                                body +
                            '</tbody>' +
                        '</table>';

                    $('#tableCustomerCarried').html(
                        table
                    );

                    setDataTable(2);
                    $('.overlay').hide();
                },
                error: function(data){
                    $('.overlay').hide();
                }
            });
        }
        
        function setCarried(customer_code, material_code) {
            $('.overlay').show();
            $.ajax({
                type: 'POST',
                url: '/customer-carried/setCarried/' + customer_code + '/' + material_code,
                data: '_token={{ csrf_token() }}',
                success: function(data){
                    console.log(data);
                    $('.overlay').hide();
                },
                error: function(data){
                    $('.overlay').show();
                }
            });
        }


    </script>
@endsection