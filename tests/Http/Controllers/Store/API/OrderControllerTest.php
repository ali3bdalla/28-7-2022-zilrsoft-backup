<?php

namespace Tests\Http\Controllers\Store\API;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Notifications\Store\IssuedOrderNotification;
use App\Notifications\Store\IssuedOrderPaymentInstructionsNotification;
use App\Scopes\DraftScope;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testItCanCreateOrder()
    {
        Notification::fake();
        list($user, $items) = $this->makeInvoiceData();
        $this->actingAs($user, 'client');
        $reponse = $this->postJson('/api/web/orders', [
            'items' => $items
        ]);
        $this->assertEquals(1, Invoice::query()->withoutGlobalScope(DraftScope::class)->count());
        $this->assertEquals(0, Invoice::query()->count());
        $this->assertEquals(count($items), InvoiceItems::query()->withoutGlobalScope(DraftScope::class)->count());
        Notification::assertSentTo($user, IssuedOrderNotification::class);
        Notification::assertSentTo($user, IssuedOrderPaymentInstructionsNotification::class);
    }
}
