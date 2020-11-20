<?php
	
	namespace Tests\Feature\Notification\Transaction;
	
	use Tests\Base\CreateManager;
	use Tests\TestCase;
	
	class TransactionIssuedTest extends TestCase
	{
		
		use  CreateManager;
		
		//RefreshDatabase
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testTransactionIssuedNotification_NotifyUser()
		{
			$manager = $this->getManager(2);
			$receiver = $this->getManager(1);
//			$manager = $this->initOrganizationAndManager();
//			$receiver = $this->createManager($manager->organization_id);
			$this->actingAs($manager, 'manager');
			$receivedGateway = $this->getAccount($manager->organization);
			$senderGateway = $this->getAccount($manager->organization);
			
			
			$response = $this->postJson(
				'/api/daily/reseller/accounts_transactions', [
					'amount' => $this->faker->numberBetween(500, 5000),
					'gateway_id' => $senderGateway->id,
					'receiver_id' => $receiver->id,
					'receiver_gateway_id' => $receivedGateway->id,
				]
			);
			
			$response->dump()->assertCreated();
		}
	}
