<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.head')
</head>
<body>

<table class="table table-hover table-striped">
    <thead>
    <tr>
        <th>Merchandiser</th>
        @foreach($days as $day)
            <th>{{ $day }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($merchandisers as $merchandiser)
        <tr>
            <td>{{ $merchandiser->fullname }}</td>
            @foreach($days as $day)
            <td>{!! Form::select("customers[]", $customers, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-placeholder' => 'Select a Customer']) !!}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>

</table>



</body>
</html>