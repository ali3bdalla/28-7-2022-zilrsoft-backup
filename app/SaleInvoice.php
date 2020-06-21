<?php

namespace App;

use App\Attributes\SaleInvoiceAttributes;
use App\DatabaseHelpers\SaleInvoiceHelper;
use App\Relationships\SaleInvoiceRelationships;
use \App\Core\CoreInvoice;

class SaleInvoice extends BaseModel
{
    //
    use SaleInvoiceRelationships, SaleInvoiceAttributes, SaleInvoiceHelper, CoreInvoice;

    protected $guarded = [];


}
