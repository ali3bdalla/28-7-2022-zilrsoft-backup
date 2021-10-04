<?php

namespace App\Repository;

use App\Dto\OrderDto;
use App\Dto\OrderPaymentDto;
use App\Enums\OrderStatusEnum;
use App\Models\Account;
use App\Models\Order;

interface OrderRepositoryContract extends BaseRepositoryContract
{
    public function getNotifiedUnPaidOrders();

    public function getUnNotifiedAutoCancellationOrders();

    public function createOrder(OrderDto $orderDto): ?Order;

    public function acceptOrderPayment(Order  $order,Account  $account);

    public function issuedOrderNotifications(Order $order);

    public function ensureUserCanManipulateOrder(Order $order, int $confirmationKey);

    public function changeOrderStatus(Order $order, OrderStatusEnum $orderStatusEnum);

    public function registerOrderPayment(Order $order, OrderPaymentDto $orderPaymentDto);
}
