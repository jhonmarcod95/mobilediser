@extends('layouts.app')

@section('headScript')
    <?php echo $map['js'] ?>
@endsection

@section('content')
    <?php echo $map['html'] ?>
@endsection
