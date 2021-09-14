<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\User;

interface OrderRepositoryContract extends BaseRepositoryContract
{
    public function createOrder(User $client, array $items,?ShippingMethod $shippingMethod = null, ?ShippingAddress $shippingAddress = null, ?string $paymentMethodId = null): ?Order;
}
