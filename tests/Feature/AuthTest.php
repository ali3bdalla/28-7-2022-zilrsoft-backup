<?php
//namespace Tests\Feature;
//
//use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//
//class  AuthTest extends TestCase {
//
////    use DatabaseTransactions;
//
//
//
//    public  function  testIfIsLoginWorksFineForManagers(){
//        $response =
//            $this->withHeaders(
//                [
//                'HTTP_Authorization' => csrf_token(),
//                'X-Requested-With'=>'XMLHttpRequest'
//                ]
//            )
//            ->postJson( '/management/login',
//                [
//                'email'=>'ali4desgin@gmail.com',
//                'password'=>'12345678'
//                ]
//            );
//
//        $response->assertStatus(302);
//
//    }
//
//
//}
