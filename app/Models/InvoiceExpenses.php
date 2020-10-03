<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceExpenses extends BaseModel
{

    use SoftDeletes;
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    //
}
