<?php

namespace Tests\Feature\Store\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateInformationTest extends TestCase
{
    // /**
    //  * test return unauthenticated status code
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldReturnUnAuthenticatedStatusCode()
    // {
    //     $response = $this->patchJson('/web/profile/update-information');
    //     $response->assertUnauthorized();
    // }


    // /**
    //  * test return validation exception status code
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldReturnValidationExceptionStatusCode()
    // {
    //     $this->actingAsUser();
    //     $response = $this->patchJson('/web/profile/update-information');
    //     $response->assertStatus(422)->assertJsonValidationErrors([
    //         'email_address', 'first_name', 'last_name'
    //     ]);
    // }


    // /**
    //  * test update information
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldUpdateInformation()
    // {
    //     $this->actingAsUser();
    //     $response = $this->patchJson('/web/profile/update-information', [
    //         'email_address' => $this->faker->email,
    //         'first_name' => $this->faker->firstName,
    //         'last_name' => $this->faker->lastName
    //     ]);

    //     $response->assertOk();
    // }

}
