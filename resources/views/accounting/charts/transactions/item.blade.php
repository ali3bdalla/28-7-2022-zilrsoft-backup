@extends('accounting.layout.master')


@section('title',$account->locale_name . ' | '. $item->locale_name)




@section('content')



    <div class="panel">

       
        <div class="panel-body">
            {{-- @include('accounting.charts.transactions.transactions') --}}
        </div>

    </div>

@stop

