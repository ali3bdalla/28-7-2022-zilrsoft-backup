<?php

namespace App;

use App\Attributes\InvoiceItemAttributes;
use App\Core\CoreIncItem;
use App\DatabaseHelpers\InvoiceItemHelper;
use App\DatabaseHelpers\KitHelper;
use App\Relationships\InvoiceItemRelationships;
use Illuminate\Database\Eloquent\Builder;


class InvoiceItems extends BaseModel
{
    use InvoiceItemRelationships, InvoiceItemAttributes, InvoiceItemHelper, KitHelper;
    use CoreIncItem;

    protected $guarded = [

    ];

    protected $appends = [
        'description'
    ];

    protected $casts = [
        'tax' => 'float',
        'total' => 'float',
        'discount' => 'float',
        'tax' => 'float',
        'net' => 'float',
        'price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {

            static::addGlobalScope('pendingItemsScope', function (Builder $builder) {
                $builder->where('is_pending', false);
            });
        }
    }


    public function fixPriceIssue($price)
    {
        $this->update([
            'price' => $price
        ]);

//        $this->item->stockMovement();

        return $this->fresh();
    }

}
