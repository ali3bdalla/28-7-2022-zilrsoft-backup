<?php

namespace Tests\Feature\Purchases;

use App\Models\Invoice;
use App\Models\Manager;
use Tests\TestCase;

class ViewPurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_purchases()
    {
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->getJson('/api/purchases');
        $response->assertStatus(200);
    }

     /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_purchases_page()
    {
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->get('/purchases');
        $response
        ->assertSee('invoice_number')
        ->assertStatus(200);
    }



    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_purchase()
    {
        $invoice = Invoice::where('invoice_type','purchase')->inRandomOrder()->first();
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->getJson("/api/purchases/{$invoice->id}");
        $response->assertStatus(200);
    }

     /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_purchase_page()
    {
        $invoice = Invoice::where('invoice_type','purchase')->inRandomOrder()->first();
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->get("/purchases/{$invoice->id}");
        $response->assertStatus(200);
    }
}
