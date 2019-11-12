<?php
namespace App\Relationships;


use App\Invoice;
use App\Item;
use App\Manager;
use App\User;

trait InvoiceItemRelationships {

    public function item(	)
    {
        return $this->belongsTo(Item::class,'item_id');
    }



    public function creator(){
        return $this->belongsTo(Manager::class,'creator_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id');
    }

}
