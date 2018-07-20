@extends('layouts.app')
@section('content')


    <section class="content-header">
        <h1>
            Offtake Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                        <h3 class="box-title">Schedule Record</h3>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="table table-responsive">
                                <table id="tblGroup" class="table table-bordered table-striped" style="white-space: nowrap; width: 100%">
                                    <thead>
                                    <th>Material</th>
                                    <th>Offtake</th>
                                    <th>Ending Balance</th>
                                    <th>Customer</th>
                                    <th>Chain</th>
                                    </thead>
                                    <tbody>
                                    @foreach($materialOfftakes as $materialOfftake)
                                        <tr>
                                            <td>{{ $materialOfftake->material_description }}</td>
                                            <td>{{ $materialOfftake->offtake }}</td>
                                            <td>{{ $materialOfftake->ending_balance }}</td>
                                            <td>{{ $materialOfftake->name . ' - ' . $materialOfftake->branch }}</td>
                                            <td>{{ $materialOfftake->description }}</td>

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

    </section>

    <script>
        $(document).ready(function() {
            $('#tblGroup').DataTable({
                'rowsGroup': [0],
                'paging'      : false,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : false

            });
        });


    </script>
@endsection
