<?php

namespace Tests\Feature\External\Smsa;

use App\Jobs\External\Smsa\SmsaGetRtlCitiesJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetRtlCitiesTest extends TestCase
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
    
        dd(SmsaGetRtlCitiesJob::dispatchNow());
    }
}
