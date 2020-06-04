<?php

namespace App;

use App\Accounting\ItemTransactionAccounting;
use App\Attributes\ItemAttributes;
use App\Attributes\KitAttributes;
use App\Core\CoreItem;
use App\DatabaseHelpers\FormattedQuery\ItemFormattedQuery;
use App\DatabaseHelpers\Invoice\ItemFreshHelper;
use App\DatabaseHelpers\ItemHelper;
use App\DatabaseHelpers\KitHelper;
use App\Processers\ItemProcesser;
use App\Relationships\ItemRelationships;
use App\Relationships\KitRelationships;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;


class Item extends Model
{
    use ItemFreshHelper, ItemRelationships, ItemAttributes, KitAttributes, KitRelationships, ItemProcesser, ItemHelper, KitHelper;
    use CoreItem, ItemTransactionAccounting, ItemFormattedQuery;
    //

    use WithFaker;

    protected $appends = [
        'locale_name',
        'warranty_title'
//			'sales_count',

    ];
    protected $casts = [
        'id' => 'integer',
        'is_has_vts' => 'boolean',
        'is_has_vtp' => 'boolean',
        'is_fixed_price' => 'boolean',
        'is_kit' => 'boolean',
        'is_service' => 'boolean',
        'is_need_serial' => 'boolean',
        'available_qty' => 'integer',
        'price' => 'float',
        'price_with_tax' => 'float',
        'is_expense' => 'boolean',
    ];
    protected $guarded = [];

    protected static function boot()
    {

        parent::boot();
        if (auth()->check()) {
            static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
        }
    }
}
