<?php

namespace App\Http\Controllers\Store\API;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
	public function getItemDetails(Request $request)
	{
		$request->validate(
			[
				'items' => 'array',
				'items.*' => 'required|integer|exists:items,id'
			]
		);

		return Item::find((array)$request->input('items'));
	}

	public function addItem(Request $request)
	{
		$data = $request->validate([
			'item_id' => 'required|integer|exists:items,id'
		]);
		$item = Item::find($data['item_id']);
		$data['session_id'] = $request->session()->getId();
		$data['price'] = $item->online_offer_price;
		$data['quantity'] = 1;
		return CartItem::create($data);
	}

	public function removeItem(Request $request)
	{
		$request->validate([
			'cart_item_id' => 'required|integer|exists:cart_items,id'
		]);
		CartItem::where([['id', $request->input('cart_item_id')], ['session_id', $request->session()->getId()]])->delete();
	}

	public function updateQuantity(Request $request)
	{
		$request->validate([
			'cart_item_id' => 'required|integer|exists:cart_items,id',
			'quantity' => 'required|numeric|min:1'
		]);
		CartItem::where([['id', $request->input('cart_item_id')], ['session_id', $request->session()->getId()]])->update([
			'quantity' => $request->input('quantity')
		]);
		return CartItem::where('id', $request->input('cart_item_id'))->first();
	}
}
