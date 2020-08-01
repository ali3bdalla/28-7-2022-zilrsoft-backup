<?php

namespace App;

use App\Attributes\InvoiceItemAttributes;
use App\Core\CoreIncItem;
use App\DatabaseHelpers\InvoiceItemHelper;
use App\DatabaseHelpers\KitHelper;
use App\Relationships\InvoiceItemRelationships;
use App\Traits\OrmNumbersTrait;
use Illuminate\Database\Eloquent\Builder;


/**
 * @property mixed item
 * @property mixed cost
 * @property mixed qty
 * @property mixed invoice_id
 * @property mixed user_id
 * @property mixed discount
 * @property mixed price
 * @property mixed invoice
 */
class InvoiceItems extends BaseModel
{
    use InvoiceItemRelationships, InvoiceItemAttributes, InvoiceItemHelper, KitHelper;
    use CoreIncItem,OrmNumbersTrait;

    protected $guarded = [

    ];

    protected $appends = [
        'description'
    ];

    protected $casts = [
        'tax' => 'float',
        'total' => 'float',
        'discount' => 'float',
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
