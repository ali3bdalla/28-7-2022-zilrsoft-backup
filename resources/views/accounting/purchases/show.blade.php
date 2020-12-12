@extends('accounting.layout.master')


@section('page_css')
    <style>
        .navbar {
            background-color: green !important;
        }
    </style>
@endsection
@section('title',__('pages/invoice.view') . ' | '. $invoice->invoice_number )

@section('buttons')

    @if(auth()->user()->id != 19)
        <a href="{{route('accounting.printer.a4',$invoice->id)}}" target="_blank" class="btn btn-default">
            <i class="fa fa-print"></i> {{ __('pages/invoice.price_a4') }}
        </a>
        @can('create purchase')
            <a href="{{route('purchases.create')}}" class="btn btn-default"><i class="fa fa-plus-square"></i> {{
        trans
        ('pages/invoice.create')
        }}</a>
        @endcan


        @if($invoice->invoice_type=='pending_purchase')
            @can('confirm purchase')
                <a href="{{route('accounting.purchases.clone',$invoice->id)}}" class="btn btn-default"><i class="fa
            fa-plus-square"></i>تاكيد الفاتورة</a>
            @endcan

        @endif


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
    @endif
@stop


@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.vendor') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{$invoice->purchase ?
                               $invoice->purchase->vendor->locale_name : null }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list"
                              class="input-group-addon">{{ trans('pages/invoice.vendor_invoice_id') }}</span>
                        <input type="text" disabled="disabled"
                               value="{{ $invoice->purchase ? $invoice->purchase->vendor_invoice_id : "" }}"
                               class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.created_at') }}</span>
                        <input type="text" name="" disabled="disabled"
                               value="{{ $invoice->created_at }}"
                               class="form-control date_field_center">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.receiver') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->purchase ?
                               $invoice->purchase->receiver->locale_name : "" }}">
                    </div>
                </div>
                @if($invoice->has_dropbox_snapshot)
                    <div class="col-md-3">
                        <div class="input-group">
                            <span id="vendors-list"
                                  class="input-group-addon">{{ trans('pages/invoice.department') }}</span>
                            <input type="text" name="" disabled="disabled"
                                   class="form-control" value="{{ $invoice->department->locale_title }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <a href="{{$invoice->dropbox_snapshot_url}}" class="btn btn-default"><i class="fa
            fa-eye"></i> فاتورة المودر</a>
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="input-group">
                            <span id="vendors-list"
                                  class="input-group-addon">{{ trans('pages/invoice.department') }}</span>
                            <input type="text" name="" disabled="disabled"
                                   class="form-control" value="{{ $invoice->department->locale_title }}">
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            @includeIf('accounting.include.invoice.view_items',[
                 'items' => $invoice->items()->withoutGlobalScope('draft')->get()
            ])

        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-3">
                    @includeIf('accounting.include.invoice.view_amounts')
                </div>
                <div class="col-md-9">
                    <div class="row">

                        <div class="col-md-12">
                            @includeIf('accounting.include.invoice.view_payments')
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @includeIf('accounting.include.invoice.view_transactions')
                            <accounting-barcode-bulk-printer-layout-component
                                    :invoice-id='@json($invoice->title)'
                                    :items='@json($invoice->items->load('item'))'
                            >
                            </accounting-barcode-bulk-printer-layout-component>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@stop
