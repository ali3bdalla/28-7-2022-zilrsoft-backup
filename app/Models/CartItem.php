<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Facades\Session;

class CartItem extends Model
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



    public static function sessionItems()
    {
        return self::where('session_id', Session::getId())->with("item")->get();
    }
}
