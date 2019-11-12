@extends('layouts.master2')


@section('title',$serial->serial)
@section('desctipion',__('sidebar.serial_history'))
@section('route',route('management.serial_history.index'))


@section('content')

    <div class="box">
        @if(!empty($serial->histories))
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">date</th>
                        <th class="text-center">event</th>
                        <th class="text-center">user</th>
                        <th class="text-center">creator</th>
                        <th class="text-center">invoice</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach($serial->histories as $history)
                        <tr>
                            <td>{{$history->created_at}}</td>
                            <td>{{$history->event}}</td>
                            <td>{{$history->user->name}}</td>
                            <td>{{$history->creator->name}}</td>
                            @if(in_array($history->event,['sale','r_sale']))
                                <td><a href="{{route('management.sales.show',$history->invoice->sale->id)}}">
                                        {{ $history->invoice->sale->prefix }}{{ $history->invoice->sale->id }}
                                    </a> </td>
                            @else
                                <td><a href="{{route('management.purchases.show',$history->invoice->purchase->id)}}">
                                        {{ $history->invoice->purchase->prefix }}{{ $history->invoice->purchase->id }}
                                    </a> </td>
                            @endif



                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@stop
