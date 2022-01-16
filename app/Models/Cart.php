<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

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
        return self::query()->with("items.item")->withCount('items')->firstOrCreate([
            'session_id' => $key
        ], []);
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
}
