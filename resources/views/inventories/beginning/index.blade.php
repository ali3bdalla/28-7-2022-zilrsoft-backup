@extends('layouts.master2')


@section('title',__('pages/invoice.inventories'))
@section('desctipion',__('pages/invoice.inventories'))
@section('route',route('management.inventories.beginning.index'))

@section('page_css')
    <style>
        th {
            text-align: center !important;
        }
    </style>
@stop

@section('content')

    <div class="box">
        <div class="card-body">
            <a href="{{route('management.inventories.beginning.create')}}" class="button is-info pull-right"><i
                        class='fa
                fa-plus-circle'></i>&nbsp; {{ __('reusable.create_invoice') }}</a>
{{--            <span class="subtitle">{{ __('pages/invoice.inventories') }}</span>--}}

            <br>
        </div>
    </div>

    <div class="card">


        @if(!empty($inventories))
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center" width="1%">{{ __('pages/invoice.id') }}</th>
                        <th class="text-center">{{ __('pages/invoice.items_count') }}</th>
                        <th class="text-center">{{ __('pages/invoice.total') }}</th>
                        <th class="text-center">{{ __('reusable.creator') }}</th>
                        <th class="text-center">{{ __('reusable.date') }}</th>
                        <th class="text-center">{{ __('reusable.manage') }}</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach($inventories as $inventory)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$inventory->items->count()}}</td>
                            <td>{{$inventory->total}}</td>
                            <td>{{$inventory->creator->name}}</td>
                            <td class="datedirection">{{$inventory->created_at}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="button is-primary" type="button"
                                            id="optionsMenu{{ $inventory->purchase->id}}" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; {{ __('reusable.manage') }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu{{ $inventory->purchase}}">
                                        <a class="dropdown-item" href="{{route('management.purchases.show',
                                    $inventory->purchase->id)}}"><i class="fa fa-eye"></i> &nbsp; {{ __('reusable.view')
                                     }}</a>

                                        <a class="dropdown-item" href="{{route('management.inventories.beginning.edit',
                                    $inventory->purchase->id)}}"><i class="fa fa-edit"></i> &nbsp; {{ __('reusable.update')
                                     }}</a>

                                    </div>
                                </div>
                            </td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $inventories->links() }}
        @endif

    </div>


@stop
