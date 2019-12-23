@extends('old.layouts.master2')


@section('title','فواتير عرض السعر')
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
            <a href="{{ route("management.sales.quotation_create") }}" class="button is-info pull-right"><i class='fa
                    fa-plus-circle'></i>&nbsp; {{ __('reusable.create_invoice') }}</a>
            <br>
            <br>

        </div>
    </div>

    <div class="card">


        {{--            <sale-table-component></sale-table-component>--}}
        @if(!empty($sales))




            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">{{ __('pages/invoice.invoice_number')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.client')  }}</th>
                        <th class="text-center ">{{ __('pages/invoice.date')  }}</th>
                        <th class="text-center">{{ __('pages/invoice.net')  }}</th>
                        <th class="text-center">{{ __('reusable.manage')  }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{$sale->invoice_id}}</td>
                            <td>{{$sale->client->name}}</td>
                            <td class="datedirection">{{$sale->created_at}}</td>
                            <td>{{$sale->invoice->net}}</td>
                            <td width="8%">

                                <a href="{{ route('management.sales.view_quotation',$sale->id) }}" class="button
                                is-primary">
                                    <i class="fa fa-eye"></i> &nbsp; {{ __('reusable.view')  }}
                                </a>


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
