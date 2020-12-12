<?php
	
	namespace Tests\Feature\Whatsapp;
	
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class ChatApiTest extends TestCase
	{
		
		
		use WithFaker;
		
		private $apiLink = "https://eu219.chat-api.com/instance193026/";
		private $apiToken = "cjjvlhjpxu3n3l4l";
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testSendingWhatsappMessage_ReturnSuccessStatus()
		{
			
			$result = file_get_contents('https://bin.chat-api.com/122ufyi1');
			echo $result;
//
//
//			dd(url());
//			$response = $this->postJson("/sendMessage", [
//				'phone' => '966504956211',
//				'body' => $this->faker->sentence
//			], ['Authorization' => 'Bearer ' . $this->apiToken]);
//
//			$response->assertStatus(200);
		}
	}
