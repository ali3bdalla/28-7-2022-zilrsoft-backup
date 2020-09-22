<?php

namespace App\Models;

use App\Models\Traits\NestingTrait;

/**
 * @method static where(array $array)
 */
class Account extends BaseModel
{

    use  NestingTrait;
    protected $guarded = [];
    protected $appends = [
        'locale_name',
        'current_amount',
        'label',
        'is_expanded',
    ];
    protected $casts = [
        'is_gateway' => 'boolean',
    ];

    public function getSerialArrayAttribute($value)
    {
        return str_split($value);
    }

    public function updateSerial()
    {

        if ($this->parent != null) {
            $parentSerial = $this->parent->serial_array;
            $serialArrayIndex = count($this->_getParentsList());
            $parentChildrenCount = $this->parent->children()->count();
            $parentSerial[$serialArrayIndex] = $parentChildrenCount;
            $serial = implode('', $parentSerial);
            $this->forceFill([
                'serial' => $serial,
            ]);
        } else {
            $count = Account::where('parent_id', 0)->count();
            $update = $this->forceFill([
                'serial' => $count . '0000000',
            ]);
        }
    }


    public function payments()
    {
        return $this->hasMany(Payment::class, 'account_id');
    }

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }


    public function getIsExpandedAttribute()
    {
        return false;
    }

    public function getLabelAttribute()
    {
        return $this->locale_name;
    }

    public function getCurrentAmountAttribute()
    {
        $totalDebitAmount = Account::whereIn('id', $this->getChildrenIncludeMe())->sum('total_debit_amount');
        $totalCreditAmount = Account::whereIn('id', $this->getChildrenIncludeMe())->sum('total_credit_amount');
        if ($this->_isCredit()) {
            return (float) ($totalCreditAmount - $totalDebitAmount);
        }
        return (float) ($totalDebitAmount - $totalCreditAmount);
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        } else {
            return $this->name;
        }
    }
    public function updateAccountBalanceUsingPipeline()
    {
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        foreach ($this->transactions as $transaction) {
            if ($transaction->type == 'credit') {
                $totalCreditAmount += (float) $transaction->amount;
            } else {
                $totalDebitAmount += (float) $transaction->amount;

            }
        }

        $this->forceFill([
            'total_credit_amount' => $totalCreditAmount,
            'total_debit_amount' => $totalDebitAmount,

        ]);

        return $this->getCurrentAmountAttribute();
    }

    public function _isDebit()
    {
        return $this->type == 'debit';
    }

    public function _isCredit()
    {
        return $this->type == 'credit';
    }

}
