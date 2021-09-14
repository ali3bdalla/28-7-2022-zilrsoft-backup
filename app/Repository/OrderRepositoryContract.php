<?php

namespace App\Repository;

use App\Dto\OrderDto;
use App\Models\Order;

interface OrderRepositoryContract extends BaseRepositoryContract
{
    public function createOrder(OrderDto $orderDto): ?Order;

    public function issuedOrderNotifications(Order $order);
}
