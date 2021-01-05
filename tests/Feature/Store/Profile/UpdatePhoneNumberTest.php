<?php

namespace Tests\Feature\Store\Profile;

use App\Models\OnlineUserPlaceholder;
use App\Models\User;
use Tests\TestCase;

class UpdatePhoneNumberTest extends TestCase
{

//     /**
//      * test return unauthenticated status code
//      * @test
//      *
//      * @return void
//      */
//     public function itShouldReturnUnAuthenticatedStatusCode()
//     {
//         $response = $this->postJson('/web/profile/update-phone-number');
//         $response->assertUnauthorized();
//     }


//     /**
//      * test return validation exception status code
//      * @test
//      *
//      * @return void
//      */
//     public function itShouldReturnValidationExceptionStatusCode()
//     {
//         $this->actingAsUser();
//         $response = $this->postJson('/web/profile/update-phone-number');
//         $response->assertStatus(422)->assertJsonValidationErrors([
//             'phone_number'
//         ]);
//     }


//     /**
//      * test send sms validation code
//      * @test
//      *
//      * @return void
//      */
//     public function itShouldSendSmsVerificationCode()
//     {

//         $this->organizationProvider();
//         $user = factory(User::class)->create(['organization_id' => 1, 'phone_number' => '966324018', 'country_code' => '966']);
//         $this->actingAs($user, 'client');
//         $response = $this->postJson('/web/profile/update-phone-number', [
//             'phone_number' => '5000000000'
//         ]);
//         $response->assertOk();

//         return $user->id;
//     }


//     /**
//      * test return validation exception invalid otp code
//      * @test
//      *
//      * @return void
//      * @depends  itShouldSendSmsVerificationCode
//      */
//     public function itShouldReturnValidationExceptionInvalidOtpCode()
//     {

//         $user = User::find(func_get_arg(0));
//         $this->actingAs($user, 'client');
//         $response = $this->postJson('/web/profile/update-phone-number', [
//             'phone_number' => '5000000000',
//             'otp' => rand(1111, 88888)
//         ]);
//         $response->assertStatus(422)->assertJsonValidationErrors([
//             'otp'
//         ]);
//     }


//     /**
//      * test change phone number
//      * @test
//      *
//      * @return void
//      * @depends  itShouldSendSmsVerificationCode
//      */
//     public function itShouldChangeUserPhoneNumber()
//     {

// //        $user = User::find(func_get_arg(0));
// //        $onlineUser = OnlineUserPlaceholder::orderBy('id', 'desc')->first();
// //        $this->actingAs($user, 'client');
// //        $response = $this->postJson('/web/profile/update-phone-number', [
// //            'phone_number' => $onlineUser->phone_number,
// //            'otp' => $onlineUser->otp
// //        ]);
// //        $response->assertOk();
//     }


}
