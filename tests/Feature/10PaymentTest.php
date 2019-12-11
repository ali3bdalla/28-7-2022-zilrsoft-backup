<?php
//
//	namespace Tests\Feature;
//
//	use Tests\TestCase;
//
//	class PaymentTest extends TestCase
//	{
//
//		/**
//		 * A basic feature test example.
//		 *
//		 * @test
//		 * @return void
//		 */
//		public function createPayment()
//		{
//
//			$data = [
//				'description' => 'hsfklksj',
//				'user_id' => 5,
//				'amount' => 50.32,
//				'voucher_type' => 'transfer',
//				'org_account_id' => 5,
//				'user_account_id' => 1,
//				'payment_type' => 'payment'
//			];
//
//			$response = $this->json('post',route('management.payments.store'),$data);
//
////			$response->dump();
//
//			$response->assertStatus(200);
//		}
//
//		protected function setUp():void
//		{
//			parent::setUp(); // TODO: Change the autogenerated stub
//			auth()->loginUsingId(1);
//		}
//	}