<?php

namespace Tests\Feature\Store\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{

    // /**
    //  * test return unauthenticated status code
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldReturnUnAuthenticatedStatusCode()
    // {
    //     $response = $this->postJson('/web/profile/update-password');
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
    //     $response = $this->postJson('/web/profile/update-password');
    //     $response->assertStatus(422)->assertJsonValidationErrors([
    //         'password', 'old_password'
    //     ]);
    // }


    // /**
    //  * test return validation exception , new password doesn't match new password confirmation
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldReturnValidationExceptionPasswordNotMatchPasswordConfirmation()
    // {
    //     $this->actingAsUser();
    //     $response = $this->postJson('/web/profile/update-password', [
    //         'password' => $this->faker->password(10),
    //         'old_password' => $this->faker->password(10),
    //     ]);
    //     $response->assertStatus(422)->assertJsonValidationErrors([
    //         'password'
    //     ]);
    // }


    // /**
    //  * test return validation exception , old password not valid
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldReturnValidationExceptionOldPasswordNotValid()
    // {
    //     $password = $this->faker->password(10);
    //     $this->actingAsUser();
    //     $response = $this->postJson('/web/profile/update-password', [
    //         'password' => $password,
    //         'password_confirmation' => $password,
    //         'old_password' => $this->faker->password(10)
    //     ]);
    //     $response->assertStatus(422)->assertJsonValidationErrors([
    //         'old_password'
    //     ]);
    // }


    // /**
    //  * test update password
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function itShouldUpdatePassword()
    // {
    //     $password = $this->faker->password(10);
    //     $oldPassword = $this->faker->password(10);
    //     $this->organizationProvider();
    //     $user = factory(User::class)->create(['organization_id' => 1, 'phone_number' => '966324018', 'country_code' => '249', 'password' => Hash::make($oldPassword)]);
    //     $this->actingAs($user, 'client');
    //     $response = $this->postJson('/web/profile/update-password', [
    //         'password' => $password,
    //         'password_confirmation' => $password,
    //         'old_password' => $oldPassword
    //     ]);
    //     $response->assertOk();
    // }


}
