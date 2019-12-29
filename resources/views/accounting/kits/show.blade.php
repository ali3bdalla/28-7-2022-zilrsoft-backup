@extends('accounting.layout.master')

@section('title',$kit->locale_name)

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/items.name_ar') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $kit->ar_name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/items.name_en') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $kit->name }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/items.barcode') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $kit->barcode }}">
                    </div>
                </div>

            </div>

        </div>
        <div class="panel-body">
            @includeIf('accounting.include.invoice.view_items',[
                 'items' => $kit->items
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
{{--                            @includeIf('accounting.include.invoice.view_payments')--}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
{{--                            @includeIf('accounting.include.invoice.view_transactions')--}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection