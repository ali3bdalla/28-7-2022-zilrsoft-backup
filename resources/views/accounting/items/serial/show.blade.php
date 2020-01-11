@extends('accounting.layout.master')


@section('title',$serial->serial . " | " . $serial->item->locale_name . ' (' .$serial->item->barcode . ')')


@section('content')

    <div class="box">
        @if(!empty($serial->histories))
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">{{ trans('pages/items.date') }}</th>
                        <th class="text-center">{{ trans('pages/payments.type') }}</th>
                        <th class="text-center">{{ trans('pages/items.movement.user') }}</th>
                        <th class="text-center">{{ trans('reusable.creator') }}</th>
                        <th class="text-center">{{ trans('pages/invoice.invoice_number') }}</th>

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
                                <td>
                                    @if($history->invoice!=null)
                                        <a href="{{route('accounting.sales.show',$history->invoice->id)}}">
                                            {{ $history->invoice->title }}
                                        </a>
                                    @endif
                                </td>
                            @else
                                <td>
                                    @if($history->invoice!=null)
                                        <a href="{{route('accounting.purchases.show',$history->invoice->id)}}">
                                            {{ $history->invoice->title }}
                                        </a>
                                    @endif
                                </td>
                            @endif


                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@stop
