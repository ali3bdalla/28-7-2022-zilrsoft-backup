<?php

namespace App\Repository;

use App\Dto\OrderDto;
use App\Dto\OrderPaymentDto;
use App\Enums\OrderStatusEnum;
use App\Models\Order;

interface OrderRepositoryContract extends BaseRepositoryContract
{
    public function createOrder(OrderDto $orderDto): ?Order;

    public function issuedOrderNotifications(Order $order);

    public function ensureUserCanManipulateOrder(Order $order, int $confirmationKey);

    public function changeOrderStatus(Order $order, OrderStatusEnum $orderStatusEnum);

    public function confirmOrderPayment(Order $order, OrderPaymentDto $orderPaymentDto);
}
