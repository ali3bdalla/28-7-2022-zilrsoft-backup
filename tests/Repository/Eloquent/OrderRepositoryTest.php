<?php

namespace Tests\Repository\Eloquent;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Order;
use App\Repository\Eloquent\OrderRepository;
use App\Repository\OrderRepositoryContract;
use App\Scopes\DraftScope;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    private OrderRepository $orderRepository;

    public function testItCanCreateOrderWithoutShipping()
    {
        list($user, $items) = $this->makeInvoiceData();
        $order = $this->orderRepository->createOrder($user, $items);
        $this->assertEquals(1, Invoice::query()->withoutGlobalScope(DraftScope::class)->count());
        $this->assertEquals(0, Invoice::query()->count());
        $this->assertEquals(count($items), InvoiceItems::query()->withoutGlobalScope(DraftScope::class)->count());
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($order->net, $order->draftInvoice->net);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = app(OrderRepositoryContract::class);
    }

}
