<?php

namespace Tests\Feature;

use App\Country;
use App\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryTest extends TestCase
{
	
	use WithFaker;
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function toCreate()
    {
    	     $name = $this->faker->country;
	    $new_country = new Country();
	    $new_country->name = $name;
	    $new_country->ar_name = $name;
	    $new_country->nationality = $name;
	    $new_country->ar_nationality = $name;
	    $new_country->country_code  = $this->faker->countryCode;
	    
	    $this->assertTrue($new_country->save());
    }
}
