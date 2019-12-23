@extends('old.layouts.master2')


@section('title',__('sidebar.purchases'))
@section('desctipion','purchases list')
@section('route',route('management.purchases.index'))

@section('page_css')
    <style>
        th {
            text-align: center !important;
        }

        .dropdown-menu {
            transform: translate3d(54px, 41px, 0px) !important;
        }
    </style>
@stop

@section('content')

    <div class="box">
        <div class="card-body">
            <a href="{{ route("management.purchases.create") }}" class="button is-info pull-right"><i class='fa
                fa-plus-circle'></i>&nbsp; {{ __('reusable.create_invoice') }}</a>
{{--            <span class="subtitle"> {{__('sidebar.purchases')}}</span>--}}

                <br>
        </div>
    </div>

    <div class="card">
        @if(!empty($purchases))
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">#</th>
                        <th class="text-center">{{ __('pages/invoice.invoice_number') }}</th>
                        <th class="text-center">{{ __('pages/invoice.vendor') }}</th>
                        <th class="text-center">{{ __('pages/invoice.receiver') }}</th>
                        <th class="text-center">{{ __('reusable.date') }}</th>
                        <th class="text-center">{{ __('pages/invoice.net') }}</th>
                        <!-- <th class="text-center">Due date</th> -->
                        <th class="text-center">{{ __('pages/invoice.issued_status') }}</th>
                        <th class="text-center">{{ __('pages/invoice.is_paid') }}</th>
                        <th class="text-center">{{ __('pages/invoice.invoice_type') }}</th>
                        <th class="text-center">{{ __('reusable.creator') }}</th>
                        <th class="text-center">{{ __('reusable.vat')  }}</th>
                        <th class="text-center">{{ __('reusable.manage') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{$purchase->id}}</td>
                            <td>{{$purchase->invoice_id}}</td>
                            <td>{{$purchase->vendor->name}}</td>
                            <td>{{$purchase->receiver->name}}</td>
                            <td>{{$purchase->created_at}}</td>
                            <td>{{$purchase->invoice->net}}</td>
                            <td>
                                {{ __('pages/invoice.' .$purchase->invoice->issued_status)  }}
                            </td>
                            <td>{{$purchase->invoice->current_status  == 'paid' ? __('pages/invoice.yes'): __('pages/invoice.no') }}</td>
                            <td>{{$purchase->invoice->description}}</td>


                            <td>{{$purchase->invoice->creator->name}}</td>
                            <td>{{$purchase->invoice->total_tax}}</td>

                            <td width="8%">

                                <div class="dropdown">
                                    <button class="button is-primary" type="button" id="optionsMenu{{
                                    $purchase->id}}"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; {{ __('reusable.manage') }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $purchase->id}}">
                                        <a class="dropdown-item"
                                           href="{{route('management.purchases.show',$purchase->id)}}"><i
                                                    class="fa fa-eye"></i> &nbsp; {{ __('reusable.view') }}</a>
                                        <a class="dropdown-item" href="{{route('management.purchases.edit',$purchase->id)
                                    }}"><i class="fa fa-redo-alt"></i>{{ __('reusable.return') }}</a>
                                        @if($purchase->invoice->is_updated)
                                            <a class="dropdown-item" href="#"><i class="fa fa-trash"></i> &nbsp; {{
                                            __('reusable.delete') }}</a>
                                        @endif
                                    </div>
                                </div>


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $purchases->links() }}
        @endif
    </div>

@stop
