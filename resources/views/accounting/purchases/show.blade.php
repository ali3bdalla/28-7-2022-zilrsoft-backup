@extends('accounting.layout.master')


@section('title',__('pages/invoice.view') . ' | '. $invoice->title )
@section('buttons')
    <a href="{{route('accounting.purchases.print',$invoice->id)}}" class="btn btn-default">
        <i class="fa fa-print"></i> {{ __('pages/invoice.price_a4') }}
    </a>
    @can("edit purchase")
        @if($invoice->is_deleted==1)
            <a href="{{route('accounting.purchases.edit',$invoice->id)}}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.return') }}
            </a>
        @endif
        @if($invoice->is_updated==1)
            <a href="{{route('accounting.purchases.destroy',$invoice->id)}}" class="btn btn-danger">
                <i class="fa fa-trash"></i> {{ __('pages/invoice.delete') }}
            </a>
        @endif
    @endcan
@stop


@section('content')

    <div class="panel">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.vendor') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->purchase->vendor->name }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list"
                              class="input-group-addon">{{ trans('pages/invoice.vendor_inc_number') }}</span>
                        <input type="text" disabled="disabled"
                               value="{{ $invoice->purchase->vendor_inc_number }}"
                               class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.created_at') }}</span>
                        <input type="text" name="" disabled="disabled"
                               value="{{ $invoice->created_at }}"
                               class="form-control date_field">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.receiver') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->purchase->receiver->name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.department') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->department->locale_title }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            @includeIf('accounting.include.invoice.view_items',[
                 'items' => $invoice->items
            ])
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-3">
                    @includeIf('accounting.include.invoice.view_amounts')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            @includeIf('accounting.include.invoice.view_expenses')
                        </div>
                        <div class="col-md-6">
                            @includeIf('accounting.include.invoice.view_payments')
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @includeIf('accounting.include.invoice.view_transactions')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@stop
