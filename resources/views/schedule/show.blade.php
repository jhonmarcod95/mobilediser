{{-- collects all old values after validation --}}
<?php
$oldValues = [];
foreach(old() as $old => $key){
    $oldValues[] = $old;
}
$oldValues = collect($oldValues);
?>
<!----------------------------------------------->

@extends('layouts.app')

@section('select2Script')
    <script>
        var ctr = 0;

        $('.items').select2();
        function cloneElement(originalId, targetId, cloneNum) {
            $('.items').select2("destroy");

            /*----------- CLONING LOGIC -------------------*/
            var copy = $('#' + originalId).clone(true);
            var cloneId = originalId + cloneNum;
            targetId = targetId + cloneNum;
            copy.attr('id', cloneId + 'puta');

            $('#' + targetId).append(copy);
            /*---------------------------------------------*/

            /*----------- SET CLONE NAMES -----------------*/
            $('#' + targetId).find('input,select,textarea').each(function () {
                $(this).attr('name', originalId + '['+cloneNum+'][]');
            });
            /*---------------------------------------------*/
            ctr++;
            $('.items').select2();
        }

        //display elements for adding & editing records with diff element ids & names
        @foreach($merchandisers as $merchandiser)
            <?php
            $id = $merchandiser->merchandiser_id;

            #get old values from collection
            $oldCustomerIndex = $oldValues->search($merchandiser->merchandiser_id.'customers');
            $oldWeekDayIndex = $oldValues->search($merchandiser->merchandiser_id.'weekdays');
            $oldRemarksIndex = $oldValues->search($merchandiser->merchandiser_id.'remarks');
            ?>

            cloneElement('customer', 'customerTd', '{{ $id }}');
            cloneElement('mon', 'weekdayTd', '{{ $id }}');
            cloneElement('tue', 'weekdayTd', '{{ $id }}');
            cloneElement('weekday', 'weekdayTd', '{{ $id }}');
            cloneElement('remarks', 'remarksTd', '{{ $id }}');

            /*-------- restore the values after validation -------*/
            //customer
            @if($oldCustomerIndex)
            $('#customer{{ $id }}').val([
                @foreach(old($oldValues[$oldCustomerIndex]) as $oldCustomer)
                {{ $oldCustomer }},
                @endforeach
            ]).trigger('change');
            @endif

            //weekday
            @if($oldWeekDayIndex)
            $('#weekday{{ $id }}').val([
                @foreach(old($oldValues[$oldWeekDayIndex]) as $oldWeekDay)
                {{ $oldWeekDay }},
                @endforeach
            ]).trigger('change');
            @endif

            //remarks
            @if($oldRemarksIndex)
                $('#remarks{{ $id }}').val('{{ old("$oldValues[$oldRemarksIndex]") }}');
            @endif
            /*---------------------------------------------------*/

        @endforeach


    </script>
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Schedules
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Schedules</li>
        </ol>
    </section>


    {{-- Elements to be cloned --}}
    <div id="rowId" hidden>
        <div id="customer" class="form-group">
            {!! Form::select('customer', $customers, null, ['class' => 'items form-control', 'data-placeholder' => 'Select a Customer', 'style' => 'width: 300px']) !!}
        </div>

        <span id="mon">{!! Form::checkbox('chk', '1', false) !!} Mon.</span>
        <span id="tue">{!! Form::checkbox('chk', '2', false) !!} Tue.</span>

        {{--<div id="weekday" class="form-group">--}}
            {{--<select name="weekday" style="width: 120px"  class="items" multiple>--}}
                {{--<option value="1">Monday</option>--}}
                {{--<option value="2">Tuesday</option>--}}
                {{--<option value="3">Wednesday</option>--}}
                {{--<option value="4">Thursday</option>--}}
                {{--<option value="5">Friday</option>--}}
                {{--<option value="6">Saturday</option>--}}
                {{--<option value="7">Sunday</option>--}}
            {{--</select>--}}
        {{--</div>--}}



        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input id="dtRange" name="dtRange" type="text"
                   class="dtRange form-control pull-right" value="">
        </div>
        <textarea id="remarks" name="remarks" class="form-control" rows="1"
                  style="width: 300px"></textarea>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ">
                        <h3 class="box-title">Schedule Record</h3>

                        <div class="pull-right" style="margin: 5px">
                            <div class="input-group input-group-sm" style="width: 180px;">
                                <input type="text" name="table_search" class="form-control pull-right"
                                       placeholder="Search">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                        </div>

                        <div class="pull-right" style="margin: 5px">
                            <div class="input-group input-group-sm" style="width: 180px;">
                                <input type="month" name="table_search" class="form-control pull-right"
                                       style="width: 150px">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-calendar"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- Content --}}
                    {!! Form::open(['url' => '/schedules/save', 'method' => 'POST']) !!}
                    <div class="box-body">

                        <div class="table-responsive mailbox-messages">

                            <div class="table table-responsive">
                                <table class="table table-bordered" style="white-space: nowrap; width: 1%">
                                    <thead>
                                    <th></th>
                                    <th>Merchandiser</th>
                                    <th>Customers</th>
                                    <th>Days</th>
                                    <th>Remarks</th>
                                    @foreach($dates as $date)
                                        <th>{{ Carbon\Carbon::parse($date)->format('M d, Y') }}</th>
                                    @endforeach
                                    </thead>
                                    <tbody>
                                    @foreach($merchandisers as $merchandiser)
                                        {!! Form::hidden('merchandisers[]', $merchandiser->merchandiser_id) !!}
                                        <tr>
                                            <td><button type="button" class="btn btn-primary"
                                                        onclick="cloneElement('customer', 'customerTd', '{{ $merchandiser->merchandiser_id }}');
                                                                 cloneElement('mon', 'weekdayTd', '{{ $merchandiser->merchandiser_id }}');"><i class="fa fa-plus"></i></button></td>
                                            <td>{{ $merchandiser->fullname }}</td>
                                            <td id="customerTd{{ $merchandiser->merchandiser_id }}"></td>
                                            <td id="weekdayTd{{ $merchandiser->merchandiser_id }}"></td>
                                            <td id="remarksTd{{ $merchandiser->merchandiser_id }}"></td>
                                            @foreach($dates as $date)
                                                {{-- display customers --}}
                                                <td>
                                                    @foreach($schedules->where('merchandiser_id', $merchandiser->merchandiser_id)
                                                                       ->where('date', $date) as $schedule)
                                                        {!! Form::checkbox('chkSchedules[]', $schedule->id) !!}
                                                        {{ $schedule->customer_name }}<br>
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button name="save" type="submit" class="btn btn-primary"><i class="fa fa-trash-o"></i> &nbsp; Save
                        </button>
                        <button name="delete" type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> &nbsp; Delete
                        </button>
                    </div>

                    {!! Form::close() !!}
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                @include('layouts.errors')
            </div>
        </div>
    </section>
@endsection
