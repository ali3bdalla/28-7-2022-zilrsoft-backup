<div class="panel panel-primary">
    <table class="table table-bordered text-center table-striped">
        <thead class="panel-heading">
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>@lang("pages/items.barcode")</th>
            <th>@lang("pages/invoice.item_name")</th>
            <th>@lang("pages/invoice.item_available_qty")</th>
            <th>@lang("pages/items.qty")</th>
            <th>@lang("pages/invoice.sales_price")</th>
            <th>@lang("pages/items.total")</th>
            <th>@lang("pages/items.subtotal")</th>
            <th>@lang("pages/items.tax")</th>
            <th>@lang("pages/items.net")</th>
        </tr>
        </thead>
        @if($items)
            <tbody>
            @foreach($items as $invoiceLine)
                @livewire("sale-receipt-item-line-component",['invoiceLine' => $invoiceLine,'index' => $loop->index], key($loop->index))
            @endforeach
            </tbody>
        @endif
    </table>
</div>
