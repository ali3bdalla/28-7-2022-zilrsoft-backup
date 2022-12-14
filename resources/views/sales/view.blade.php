@extends('accounting.layout.master')

@section('title',__('pages/invoice.view') . ' | '. $invoice->invoice_number )

@section('page_css')
    @if($invoice->is_draft)
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

        @if($invoice->is_draft)
            <a href="{{route('sales.drafts.to_invoice',$invoice->id)}}" class="btn btn-default">
                <i class="fa fa-copy"></i> {{ __('pages/invoice.quotation_to_sale') }}
            </a>
        @else
            @can('create sale')
                <a href="{{route('sales.create')}}" class="btn btn-default"><i class="fa fa-plus-square"></i> {{
        trans
        ('pages/invoice.create')
        }}</a>
            @endcan
            @if($invoice->invoice_type=='sale')
                @can("edit sale")
                    @if($invoice->is_deleted==0)
                        <a href="{{route('sales.edit',$invoice->id)}}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.return') }}
                        </a>
                    @endif

                @endcan
                @can("edit sale")
                    @if($invoice->is_deleted==0)
                        <a href="{{route('sales.add_warranty_tracing',$invoice->id)}}" class="btn btn-info">
                            تتبع الضمان
                        </a>
                    @endif

                @endcan
            @endif
        @endif
    @endif


    @php
        $order = $invoice->getOrder()
    @endphp

    @if($order)
        @if($order->status == 'ready_for_shipping')
            <a href="/store/shipping/{{$order->shipping_method_id}}/{{$order->id}}/create-order-transaction"
               class="btn btn-default">
                <i class="fa fa-copy"></i> انشاء بوليصة
            </a>
        @endif
    @endif
@stop


@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.client') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{
                               $invoice->user_alice_name=="" ?$invoice->user->locale_name:
                               $invoice->user_alice_name}}">

                    </div>
                </div>
                <div class="col-md-2">
                    <invoice-alice-name-form-pop :invoice-id="{{$invoice->id}}"  alice-name="{{$invoice->user_alice_name}}"></invoice-alice-name-form-pop>
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
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">{{ trans('pages/invoice.salesman') }}</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->manager->locale_name }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span id="vendors-list" class="input-group-addon">رقم الجوال</span>
                        <input type="text" name="" disabled="disabled"
                               class="form-control" value="{{ $invoice->contact_phone_number }}">
                    </div>
                </div>
                <div class="col-md-4">
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
                 'items' => $invoice->items()->withoutGlobalScope(App\Scopes\DraftScope::class)->get()
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
                            <div class="panel panel-primary">
                                <h4 class="panel-heading">ملاحظات</h4>
                                <div class="panel-body">
                                    {{ $invoice->notes }}
                                </div>
                            </div>
                        </div>
                        @if($invoice->invoice_type!='quotation')
                            <div class="col-md-12">
                                @includeIf('accounting.include.invoice.view_payments')
                            </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            @includeIf('accounting.include.invoice.view_transactions')
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
