@extends('accounting.layout.master')

@section('title',__('pages/invoice.view') . ' | '. $invoice->invoice_number )



@section('page_css')
    @if($invoice->invoice_type=='quotation')
        <style>
            .navbar {
                background-color: #b8b83a !important;
            }
        </style>
    @endif
@endsection


@section('buttons')

    @if(auth()->user()->id != 19)
        <a href="{{route('accounting.printer.a4',$invoice->id)}}" target="_blank" class="btn btn-default">
            <i class="fa fa-print"></i> {{ __('pages/invoice.price_a4') }}
        </a>

        <accounting-print-receipt-layout-component
                :invoice-id="{{$invoice->id}}"></accounting-print-receipt-layout-component>

        @if($invoice->invoice_type=='quotation')
            <a href="{{route('accounting.quotations.edit',$invoice->id)}}" class="btn btn-default">
                <i class="fa fa-copy"></i> {{ __('pages/invoice.quotation_to_sale') }}
            </a>
        @else
            @can('create sale')
                <a href="{{route('accounting.sales.create')}}" class="btn btn-default"><i class="fa fa-plus-square"></i> {{
        trans
        ('pages/invoice.create')
        }}</a>
            @endcan
            @if($invoice->invoice_type=='sale')
                @can("edit sale")
                    @if($invoice->is_deleted==0)
                        <a href="{{route('accounting.sales.edit',$invoice->id)}}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.return') }}
                        </a>
                    @endif
                    {{--        @if(!$invoice->is_updated)--}}
                    {{--            <a href="{{route('accounting.sales.destroy',$invoice->id)}}" class="btn btn-danger">--}}
                    {{--                <i class="fa fa-trash"></i> {{ __('pages/invoice.delete') }}--}}
                    {{--            </a>--}}
                    {{--        @endif--}}
                @endcan
            @endif
        @endif

    @endif

@stop


@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.client') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{
                               $invoice->user_alice_name=="" ?$invoice->user->locale_name:
                               $invoice->user_alice_name}}">
                        <InvoiceAliceNameFormPop :alice_name="{{$invoice->user_alice_name}}"></InvoiceAliceNameFormPop>
                    </div>
                </div>

                <div class="col-md-6">
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
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.salesman') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->manager->locale_name }}">
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
                 'items' => $invoice->items()->withoutGlobalScope(\App\Scopes\DraftScope::class)->get()
            ])
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-3">
                    @includeIf('accounting.include.invoice.view_amounts')
                </div>


                @if($invoice->invoice_type!='quotation')
                    <div class="col-md-9">
                        <div class="row">

                            <div class="col-md-12">

                                @includeIf('accounting.include.invoice.view_payments')
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                @includeIf('accounting.include.invoice.view_transactions')
                            </div>

                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>


@stop
