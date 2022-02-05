<tr
>
    <td>
        <button
                class="btn btn-danger btn-xs"
        >
            <i class="fa fa-trash"></i>
        </button>
    </td>
    <td>
    </td>
    <td>
    </td>
    <td>
        {{ $invoiceLine["item"]["barcode"]}}
    </td>
    <td>
        {{ $invoiceLine["item"]["locale_name"] }}
    </td>
    <td>
        {{ $invoiceLine["item"]["available_qty"] }}
    </td>
    <td>
        <input type="text" class="form-control border border-danger"  wire:model.lazy="invoiceLine.qty"></input>
    </td>

    <td>
        <input type="text" class="form-control"  wire:model.lazy="invoiceLine.price"></input>
    </td>

    <td>
        {{ showMoney($this->total) }}
    </td>
    <td>
        {{ showMoney($this->subtotal) }}
    </td>

    <td>
        {{ showMoney($this->tax) }}
    </td>
    <td>
        {{ showMoney($this->net) }}
    </td>
</tr>
