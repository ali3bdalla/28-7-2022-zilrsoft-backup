@extends('accounting.layout.master')


@section('page_css')
    <style>
        .navbar {
            background-color: green !important;
        }
    </style>
@endsection
@section('title',__('pages/invoice.view') . ' | '. $invoice->title )
@section('buttons')


@stop


@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
{{--                <div class="col-md-4">--}}
{{--                    <div class="input-group">--}}
{{--                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.vendor') }}</span>--}}
{{--                        <input type="text" name="" disabled="disabled"--}}
{{--                               class="form-control" value="{{ $invoice->purchase->vendor->locale_name }}">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4">--}}
{{--                    <div class="input-group">--}}
{{--                        <span id="vendors-list"--}}
{{--                              class="input-group-addon">{{ trans('pages/invoice.vendor_inc_number') }}</span>--}}
{{--                        <input type="text" disabled="disabled"--}}
{{--                               value="{{ $invoice->purchase->vendor_inc_number }}"--}}
{{--                               class="form-control">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-4">--}}
{{--                    <div class="input-group">--}}
{{--                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.created_at') }}</span>--}}
{{--                        <input type="text" name="" disabled="disabled"--}}
{{--                               value="{{ $invoice->created_at }}"--}}
{{--                               class="form-control date_field_center">--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="input-group">--}}
{{--                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.receiver') }}</span>--}}
{{--                        <input type="text" name="" disabled="disabled"--}}
{{--                               class="form-control" value="{{ $invoice->purchase->receiver->locale_name }}">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    <div class="input-group">--}}
{{--                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.department') }}</span>--}}
{{--                        <input type="text" name="" disabled="disabled"--}}
{{--                               class="form-control" value="{{ $invoice->department->locale_title }}">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
