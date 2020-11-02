<?php

namespace Tests\Feature\ShippingAddress;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateShippingAddress extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateShippingAddress_ReturnAddressInstance()
    {
        $response = $this->post('/api/web/shipping_addresses');
//        $response->
    }
}
