<?php

namespace App\Repository;

use App\Dto\OrderDto;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\User;

interface OrderRepositoryContract extends BaseRepositoryContract
{
    public function createOrder(OrderDto $orderDto): ?Order;

    public function issuedOrderNotifications(Order $order);
}
