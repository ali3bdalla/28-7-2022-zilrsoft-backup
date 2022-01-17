<?php

namespace App\Http\Controllers\Store\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
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

		return Item::whereIn('id', (array)$request->input('items', []))->get();
	}
	public function addItem(Request $request)
	{
		$data = $request->validate([
			'item_id' => 'required|integer|exists:items,id'
		]);
		$item = Item::find($data['item_id']);
		$data['price'] = $item->online_offer_price;
		$data['quantity'] = 1;
		return Cart::addItem($data);
	}
	public function removeItem(Request $request)
	{
		$request->validate([
			'cart_item_id' => 'required|integer|exists:cart_items,id'
		]);
		Cart::removeItem($request->input('cart_item_id'));
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
	public function updateCity(Request $request)
	{
		$request->validate([
			'city_id' => 'required|integer|exists:cities,id'
		]);
		Cart::getSessionCart()->update([
			'city_id' => $request->input('city_id')
		]);
	}
	public function updateShippingMethod(Request $request)
	{
		$request->validate([
			'shipping_method_id' => 'required|integer|exists:shipping_methods,id'
		]);
		Cart::getSessionCart()->update([
			'shipping_method_id' => $request->input('shipping_method_id')
		]);
	}
	public function updateShippingAddress(Request $request)
	{
		$request->validate([
			'shipping_address_id' => 'required|integer|exists:shipping_addresses,id'
		]);
		Cart::getSessionCart()->update([
			'shipping_address_id' => $request->input('shipping_address_id')
		]);
	}
}
