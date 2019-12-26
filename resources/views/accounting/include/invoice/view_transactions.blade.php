@can('view transactions')
    <div class="panel panel-info">
        <div class="panel-heading">
            <label>{{ trans('sidebar.transactions') }}</label>
        </div>
        <table class="table table-bordered table-hover text-center">
            <thead>
            <th>{{ trans('pages/transactions.date') }}</th>
            <th>{{ trans('pages/transactions.account_name') }}</th>
            <th>{{ trans('pages/transactions.debit') }}</th>
            <th>{{ trans('pages/transactions.credit') }}</th>
            </thead>


            @if($invoice->invoice_type=='sale')
                @includeIf('accounting.include.invoice.transactions.sale')
            @elseif($invoice->invoice_type=='r_sale')
                @includeIf('accounting.include.invoice.transactions.return_sale')
            @elseif($invoice->invoice_type=='r_purchase')
                @includeIf('accounting.include.invoice.transactions.return_purchase')
            @else
                @includeIf('accounting.include.invoice.transactions.purchase')
            @endif

        </table>
    </div>
@endcan