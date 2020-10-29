@extends('accounting.layout.master')


@section('title',$serial->serial . " | " . $serial->item->locale_name . ' (' .$serial->item->barcode . ')')

@section('content')

    <div class="box">
        @if(!empty($histories))
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">{{ trans('pages/items.date') }}</th>
                        <th class="text-center">{{ trans('pages/invoice.invoice_type') }}</th>
                        <th class="text-center">{{ trans('pages/items.movement.user') }}</th>
                        <th class="text-center">{{ trans('reusable.creator') }}</th>
                        <th class="text-center">{{ trans('pages/invoice.invoice_number') }}</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach($histories as $history)
                        <tr>
                            <td>{{$history->created_at}}</td>
                            <td>
                            @if($history->event == 'in_stock')
مشتريات
                            @else

{{ trans('pages/invoice.' . $history->event) }}
                            @endif
                            </td>
                            <td>{{$history->user->locale_name}}</td>
                            <td>{{$history->creator->locale_name}}</td>
                            @if(in_array($history->event,['sale','return_sale']))
                                <td>
                                    @if($history->invoice!=null)
                                        <a href="{{route('sales.show',$history->invoice->id)}}">
                                            @if($history->invoice->invoice_number)

                                            {{ $history->invoice->invoice_number }}

                                            @else

                                            {{$history->invoice->id}}
                                            @endif
                                        </a>
                                    @endif
                                </td>
                            @else
                                <td>
                                    @if($history->invoice!=null)
                                        <a href="{{route('purchases.show',$history->invoice->id)}}">
                                           @if($history->invoice->invoice_number)

                                            {{ $history->invoice->invoice_number }}
                                            @else
                                            {{$history->invoice->id}}
                                            @endif
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
