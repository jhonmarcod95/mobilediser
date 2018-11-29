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
                        <div class="row">
                            {!! Form::open(['id' => 'formFilter', 'url' => '/customer-carried', 'method' => 'GET']) !!}

                            <div class="col-md-4">
                                <label>Customer: </label>
                                {!! Form::select('customer_ids[]', $search_customers, null, ['class' => 'form-control select2', 'multiple', 'required']) !!}
                            </div>

                            <div class="col-md-4">
                                <label>Material: </label>
                                {!! Form::select('material_ids[]', $search_materials, null, ['class' => 'form-control select2', 'multiple', 'required']) !!}
                            </div>

                            <div class="col-md-4">
                                <br>
                                <button class="btn btn-primary" type="button" onclick="retrieveCustomerCarried()"><i class="fa fa-search"></i>&nbsp; Search</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="table table-responsive" id="tableCustomerCarried"></div>

                        </div>
                        <div class="row">
                            <div id="response-details" class="col-md-12">
                            </div>
                        </div>
                    </div>

                    <div id="loading-carried"></div>
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
            showLoading('loading-carried', true);

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
                    showLoading('loading-carried', false);
                },
                error: function(data){
                    showLoading('loading-carried', false);
                }
            });
        }
        
        function setCarried(customer_code, material_code) {
            showLoading('loading-carried', true);
            $.ajax({
                type: 'POST',
                url: '/customer-carried/setCarried/' + customer_code + '/' + material_code,
                data: '_token={{ csrf_token() }}',
                success: function(data){
                    showLoading('loading-carried', false);

                },
                error: function(data){
                    $("#response-details").html(showErrorAlert(data));
                    showLoading('loading-carried', false);
                }
            });
        }


    </script>
@endsection