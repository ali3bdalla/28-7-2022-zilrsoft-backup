<?php

namespace App\Models;

use App\Attributes\SaleInvoiceAttributes;
use App\Relationships\SaleInvoiceRelationships;

class SaleInvoice extends BaseModel
{
    //
    use SaleInvoiceRelationships, SaleInvoiceAttributes;

    protected $guarded = [];


}
