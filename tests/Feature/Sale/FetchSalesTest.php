<?php

namespace Tests\Feature\Sale;

use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchSalesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFetchSales_GetPaginationResponse()
    {
		$this->actingAs(Manager::first());
		
        $response = $this->get('/api/sales');

        $response->assertJson([
        	'current_page' => 1
        ])->assertStatus(200);
    }
}
