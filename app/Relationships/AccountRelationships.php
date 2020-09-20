<?php

namespace App\Relationships;

use App\Models\Payment;
use App\Models\Transaction;

trait AccountRelationships
{

    public function paymentable()
    {
        return $this->morphMany(Payment::class, 'paymentable');
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

    // public function credit_transaction()
    // {
    //     return $this->hasMany(Transaction::class, 'account_id')->where('type', 'credit');
    // }

    // public function debit_transaction()
    // {
    //     return $this->hasMany(Transaction::class, 'account_id')->where('type', 'debit');
    // }
}
