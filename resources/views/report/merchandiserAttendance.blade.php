@extends('layouts.app')
@section('content')


    <section class="content-header">
        <h1>
            Merchandiser Attendance
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Reports</a></li>
            <li class="active">Merchandiser Attendance</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Search Merchandiser: </label>
                                    {!! Form::select('merchandiser_ids[]', $merchandisers, null, ['id' => 'merchandiser_ids', 'class' => 'form-control select2', 'multiple']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date From</label>
                                    <input type="date" id="date-from" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">Date To</label>
                                    <input type="date" id="date-to" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-muted">&nbsp;</label><br>
                                    <button id="btn-filter" class="btn btn-default btn-sm"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
