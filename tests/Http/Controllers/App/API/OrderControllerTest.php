<?php

namespace Tests\Http\Controllers\App\API;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\App\API\OrderController;
use App\Models\InvoiceItems;
use App\Models\Order;
use App\Notifications\Store\IssuedOrderNotification;
use App\Scopes\DraftScope;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testItCanAcceptOrderPayment()
    {
//        Notification::fake();
        $manager = $this->createManager();
        list($user, $items) = $this->makeInvoiceData();
        $this->actingAs($user, 'client');
        $this->postJson('/api/web/orders', [
            'items' => $items
        ])->assertCreated();
//        $this->actingAs($manager);
//        $order = Order::first();
//        $order->update([
//            'status' => OrderStatusEnum::pending()
//        ]);
//        dd($order);
//        $this->assertEquals(count($items), InvoiceItems::query()->withoutGlobalScope(DraftScope::class)->count());
//        Notification::assertSentToTimes($user, IssuedOrderNotification::class, 6);
    }
}
