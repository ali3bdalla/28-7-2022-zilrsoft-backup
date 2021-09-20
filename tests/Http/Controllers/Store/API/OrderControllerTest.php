<?php

namespace Tests\Http\Controllers\Store\API;

use App\Models\InvoiceItems;
use App\Notifications\Store\IssuedOrderNotification;
use App\Scopes\DraftScope;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testItCanCreateOrder()
    {
//        Notification::fake();
        list($user, $items) = $this->makeInvoiceData();
        $this->actingAs($user, 'client');
        $this->postJson('/api/web/orders', [
            'items' => $items
        ])->assertCreated();
        $this->assertEquals(count($items), InvoiceItems::query()->withoutGlobalScope(DraftScope::class)->count());
//        Notification::assertSentToTimes($user, IssuedOrderNotification::class,6);
    }
}
