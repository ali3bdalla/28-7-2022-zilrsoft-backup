<?php

namespace Tests\Feature\Backend\Accounting\CloseYear;

use App\Jobs\Accounting\CloseYear\NormalizeIncomesExpensesJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NormalizeIncomesExpensesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function itShouldNormalizeIncomesAndExpenses()
    {
        $this->actingAsManager();
        NormalizeIncomesExpensesJob::dispatchNow();
        
    }
}
