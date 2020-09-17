<?php

namespace App\Models;

use App\Attributes\InvoiceAttributes;
use App\Relationships\InvoiceRelationship;
use App\Traits\OrmNumbersTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed organization_id
 * @property mixed creator_id
 * @property mixed user_id
 * @property mixed invoice_type
 * @property mixed net
 * @property mixed tax
 * @property mixed id
 * @property mixed branch_id
 * @property mixed department_id
 * @property mixed sale
 * @property mixed purchase
 * @property mixed total
 */
class Invoice extends Model
{

    use InvoiceRelationship;
    use InvoiceAttributes;
    use SoftDeletes;
    use OrmNumbersTrait;

    protected static function boot()
    {
        parent::boot();
     
        // if (auth()->check() && !auth()->user()->can('manage branches')) {
        //     static::addGlobalScope('currentManagerInvoicesOnly', function (Builder $builder) {
        //         $builder->where('creator_id', auth()->user()->id);
        //     });
        // }
    }

    protected $appends = [
        'description',
        'title',
        'user_id',
    ];
    protected $guarded = [];

    protected $casts = [
        'printable_price' => 'boolean',
    ];

}
