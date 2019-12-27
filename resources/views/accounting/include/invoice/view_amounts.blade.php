<div class="panel">
    <div class="panel-header">
        {{ __('pages/invoice.invoice_data') }}
    </div>
    <div class="panel-body text-center">
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.total') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ $invoice->total }}"
                           disabled="">
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.discount') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ $invoice->discount_value }}"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.subtotal') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ $invoice->subtotal }}"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.tax') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ $invoice->tax }}"
                           disabled="">
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.net') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ $invoice->net }}"
                           disabled="">
                </div>
            </div>
        </div>
        <hr>
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-5"><label>{{ __('pages/invoice.paid') }}</label></div>
                <div class="col-md-5">
                    <input type="text" class="form-control input-xs amount-input" value="{{ money_format("%i",$invoice->net
                    - $invoice->remaining )}}"
                           disabled="">
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-12">
        @includeIf('accounting.include.invoice.view_expenses')
    </div>
</div>