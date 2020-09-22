<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends BaseModel
{

    use SoftDeletes;

    protected $guarded = [];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }


    public function receiver()
    {
        return $this->belongsTo(Manager::class, 'receiver_id');
    }


}
