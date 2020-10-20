<div class="panel">
    <div class="panel-header">
        {{ __('pages/invoice.invoice_data') }}
    </div>
    <div class="panel-body text-center">
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.total') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ displayMoney($invoice->total) }}"
                           disabled="">
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.discount') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ displayMoney($invoice->discount) }}"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.subtotal') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{displayMoney($invoice->subtotal) }}"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.tax') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ displayMoney($invoice->tax) }}"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.net') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ displayMoney($invoice->net) }}"
                           disabled="">
                </div>
            </div>
        </div>
        {{-- <hr> --}}
        {{-- <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.paid') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ money_format("%i",$invoice->net
                    - $invoice->remaining )}}"
                           disabled="">
                </div>
            </div>
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.current_status') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input"
                           value="{{ $invoice->current_status=='paid' ? trans('pages/invoice.paid') :  trans('pages/invoice.credit') }}"
                           disabled="">
                </div>
            </div>
        </div> --}}

    </div>
    <div class="col-md-12">
        @if(!in_array($invoice->invoice_type,['sale','return_sale']))
            @includeIf('accounting.include.invoice.view_expenses')
        @endif
    </div>
</div>