<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;
/**
 * @method static self sale()
 * @method static self purchase()
 * @method static self return_purchase()
 * @method static self return_sale()
 * @method static self beginning_inventory()
 * @method static self inventory_adjustment()
 */
class InvoiceTypeEnum extends Enum
{

}
