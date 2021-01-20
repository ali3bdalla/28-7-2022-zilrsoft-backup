<?php

namespace Tests\Feature\External\Smsa;

use App\Jobs\External\Smsa\SmsaCreateShippmentJob;
use Tests\TestCase;

class CreateShippmentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        // $response = $this->get('/');

        // $response->assertStatus(200);
    
        SmsaCreateShippmentJob::dispatchNow();
    }
}
