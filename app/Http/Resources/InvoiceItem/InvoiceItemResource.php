<?php

namespace App\Http\Resources\InvoiceItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'invoice_id' => $this->invoice_id,
            'creator_id' => $this->creator_id,
            'item_id' => $this->item_id,
            'user_id' => $this->user_id,
            'returned_qty' => (int)$this->returned_qty,
            'qty' => (int)$this->qty,
            'tax' => moneyFormatter($this->tax),
            'price' => moneyFormatter($this->price),
            'total' => moneyFormatter($this->total),
            'subtotal' => moneyFormatter($this->subtotal),
            'discount' => moneyFormatter($this->discount),
            'unit_price' => moneyFormatter($this->subtotal / $this->qty,2),
            'invoice_type' => $this->invoice_type,
            'belong_to_kit' => $this->belong_to_kit,
            'parent_kit_id' => $this->parent_kit_id,
            'is_kit' => $this->is_kit,
            'show_price_in_print_mode' => $this->show_price_in_print_mode,
            'is_draft' => (bool)$this->is_draft,
            'available_qty' => (int)$this->available_qty,
            'cost' => (float)moneyFormatter($this->cost),
            'total_stock_cost_amount' => moneyFormatter((float)$this->total_stock_cost_amount),
            'profit' => (float)$this->profit,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'description' => $this->description,
            'invoice_url' => $this->invoice_url,
            'invoice_number' => $this->invoice_number,
            'user' => $this->user,
            'creator' => $this->creator,
            'invoice' => $this->invoice,


        ];
//        return parent::toArray($request);
    }
}
