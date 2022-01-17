<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ["id"];
    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
    public static function getSessionCart()
    {
        $key = Session::get('cart_key') ?? Str::random(80);
        Session::put("cart_key", $key);
        $cart = self::query()->firstOrCreate([
            'session_id' => $key
        ], []);
        return $cart->load("items.item", "shippingMethod")->loadCount("items");
    }
    public static function removeItem($id)
    {
        $cart = self::getSessionCart();
        $cart->items()->where('id', $id)->delete();
    }
    public static function addItem(array $data)
    {
        $cart = self::getSessionCart();
        return $cart->items()->create($data);
    }
    public static function isEmpty(): bool
    {
        return self::getSessionCart()->items()->count() === 0;
    }
    public static function hasShippingAddress()
    {
        return !empty(self::getSessionCart()->shipping_address_id);
    }
    public static function hasCity()
    {
        return !empty(self::getSessionCart()->city_id);
    }
    public static function hasShippingMethod()
    {
        return !empty(self::getSessionCart()->shipping_method_id);
    }
    public static function needShippingAddress(): bool
    {

        return self::getSessionCart()->shippingMethod->is_required_shipping_address;
    }

    public static function isReady(): bool
    {

        return !self::isEmpty() && self::hasShippingMethod() &&
            (self::hasShippingAddress() || !self::needShippingAddress()
            );
    }

    public static function list()
    {
        return self::getSessionCart()->items()->with('item')->get();
    }

    public static function canBeHandled()
    {
        foreach (self::list() as $cartItem) {
            if (!$cartItem->canBeHandled()) {
                return false;
            }
        }
        return true;
    }
    public static function earse()
    {
        $cart = Cart::getSessionCart();
        $cart->items()->delete();
        $cart->delete();
        Session::put("cart_key", Str::random(80));
    }
}
