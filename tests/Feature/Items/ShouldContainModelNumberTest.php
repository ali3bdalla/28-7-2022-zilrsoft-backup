<?php

namespace Tests\Feature\Items;

use App\Models\ItemFilters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShouldContainModelNumberTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShouldContainModelNumber_FetchData()
    {
	    $itemsHaveModelNumber = ItemFilters::where('filter_id',38)->count();
	    dd($itemsHaveModelNumber);
    }
}
