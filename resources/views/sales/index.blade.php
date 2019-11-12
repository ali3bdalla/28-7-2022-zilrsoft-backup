@extends('layouts.master2')


@section('title',__('sidebar.sales'))
@section('desctipion','sales list')
@section('route',route('management.sales.index'))



@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/invoice'))'
    </script>
@stop


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
                <a href="{{ route("management.sales.create") }}" class="button is-info pull-right"><i class='fa
                    fa-plus-circle'></i>&nbsp; {{ __('reusable.create_invoice') }}</a>
                <span class="subtitle"> {{__('sidebar.sales')}}</span>

            </div>
        </div>

        <div class="card">



{{--            <sale-table-component></sale-table-component>--}}
        @if(!empty($sales))




            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">#</th>
                        <th class="text-center">{{ __('pages/invoice.invoice_number')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.client')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.salesman')  }}</th>
                        <th class="text-center ">{{ __('pages/invoice.date')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.net')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.issued_status')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.is_paid')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.invoice_type')  }}</th>
                        <th class="text-center">{{ __('reusable.creator')  }}</th>
                        <th class="text-center">{{ __('reusable.vat')  }}</th>
                        <th class="text-center">{{ __('reusable.manage')  }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{$sale->id}}</td>
                            <td>{{$sale->invoice_id}}</td>
                            <td>{{$sale->client->name}}</td>
                            <td>{{$sale->salesman->name}}</td>
                            <td class="datedirection">{{$sale->created_at}}</td>
                            <td>{{$sale->invoice->net}}</td>
                            <td>
                                {{ __('pages/invoice.' .$sale->invoice->issued_status)  }}
                            </td>
                            <td>{{$sale->invoice->current_status  == 'paid' ? __('pages/invoice.yes'): __('pages/invoice.no') }}</td>
                            <td>{{$sale->invoice->description}}</td>
                            <td>{{$sale->invoice->creator->name}}</td>
                            <td>{{$sale->invoice->tax}}</td>
                            <td width="8%">

                                <div class="dropdown">
                                    <button class="button is-primary" type="button" id="optionsMenu{{ $sale->id}}"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; {{ __('reusable.manage')  }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $sale->id}}">
                                        <a class="dropdown-item" href="{{route('management.sales.show',$sale->id)}}"><i
                                                    class="fa fa-eye"></i> &nbsp; {{ __('reusable.view')  }}</a>
                                        <a class="dropdown-item" href="{{route('management.sales.edit',$sale->id)}}"><i
                                                    class="fa
    							     fa-redo-alt"></i> &nbsp; {{ __('reusable.return')  }}</a>
                                        <a class="dropdown-item" href="#"><i class="fa fa-trash"></i> &nbsp; {{ __
                                        ('reusable.delete')  }}</a>
                                        <a class="dropdown-item" href="{{route('management.sales.clone',$sale->id)}}"><i
                                                    class="fa fa-copy"></i> &nbsp; {{ __
                                        ('reusable.copy')  }}</a>


                                    </div>
                                </div>


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $sales->links() }}
        @endif

    	</div>


@stop
