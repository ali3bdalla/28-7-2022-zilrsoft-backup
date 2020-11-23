<?php

namespace Tests\Feature\DB;

use App\Models\Filter;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class TimestampTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        factory(User::class)->create();
        factory(Filter::class)->create(['organization_id' => 1]);
        factory(Manager::class)->create(['organization_id' => 1]);
    }
}
