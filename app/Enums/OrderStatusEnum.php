<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;
/**
 * @method static self issued()
 * @method static self pending()
 * @method static self paid()
 * @method static self in_progress()
 * @method static self ready_for_shipping()
 * @method static self shipped()
 * @method static self delivered()
 * @method static self returned()
 */
class OrderStatusEnum extends Enum
{

}
