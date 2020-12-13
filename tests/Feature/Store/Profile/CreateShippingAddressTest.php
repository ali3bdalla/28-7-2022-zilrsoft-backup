<?php

namespace Tests\Feature\Store\Profile;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateAddressTest extends TestCase
{
    /**
     * test return unauthenticated status code
     * @test
     *
     * @return void
     */
    public function itShouldReturnUnAuthenticatedStatusCode()
    {
        $response = $this->postJson('/web/profile/create-shipping-address');
        $response->assertUnauthorized();
    }


    /**
     * test return validation exception status code
     * @test
     *
     * @return void
     */
    public function itShouldReturnValidationExceptionStatusCode()
    {
        $this->actingAsUser();
        $response = $this->postJson('/web/profile/create-shipping-address');
        $response->assertStatus(422)->assertJsonValidationErrors([
            'city_id', 'first_name', 'last_name', 'phone_number', 'description'
        ]);
    }


    /**
     * test update information
     * @test
     *
     * @return void
     */
    public function itShouldUpdateInformation()
    {
        $this->actingAsUser();
        $response = $this->postJson('/web/profile/create-shipping-address', [
            'city_id' => factory(City::class)->create()->id,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone_number' => '556045415',
            'description' => $this->faker->text(50)
        ]);

        $response->assertCreated();
    }

}
