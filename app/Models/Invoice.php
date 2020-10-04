<?php

namespace App\Models;

use App\Attributes\InvoiceAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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
 * @property mixed invoice_number
 * @property mixed items
 * @property mixed managed_by_id
 * @property mixed vendor_invoice_number
 * @method static create(array $array)
 */
class Invoice extends Model
{

    use InvoiceAttributes;
    use SoftDeletes;

    protected $appends = [
        // 'description',
        // 'title',
        // 'user_id',
    ];
    protected $guarded = [];
    protected $casts = [
        'printable_price' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();



        if (auth()->check())
        {
            static::addGlobalScope('organization', function (Builder $builder) {
                $builder->where('organization_id', auth()->user()->id);
            });
        }
       
        static::addGlobalScope('draft', function (Builder $builder) {
            $builder->where('is_draft', false);
        });


        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });

        if (auth()->check() && !auth()->user()->can('manage branches')) {
            static::addGlobalScope('manager', function (Builder $builder) {
                $builder->where('creator_id', auth()->user()->id);
            });
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }


    public function manager()
    {
        return $this->belongsTo(Manager::class, 'managed_by_id');

    }


    public function expenses()
    {
        return $this->hasMany(InvoiceExpenses::class, 'invoice_id');
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id')->withoutGlobalScope('draft');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'invoice_id');
    }

    public function serial_history()
    {
        return $this->hasOne(SerialHistory::class, 'invoice_id');
    }

    public function child()
    {
        return $this->belongsTo(Invoice::class, 'parent_invoice_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

}
