<?php
	
	namespace Tests\Feature\Whatsapp;
	
	use AliAbdalla\Whatsapp\Core\WhatsappCore;
	use AliAbdalla\Whatsapp\Whatsapp;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class WhatsappConnectionTest extends TestCase
	{
		use WithFaker;
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testWhatsappApiConnection()
		{
			Whatsapp::sendMessage('work', '966504956211');
		}
	}
