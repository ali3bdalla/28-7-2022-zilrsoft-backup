<?php
	
	/** @var Factory $factory */
	
	use App\Models\ResellerClosingAccount;
	use App\Models\TransactionsContainer;
	use Faker\Generator as Faker;
	use Illuminate\Database\Eloquent\Factory;
	
	$factory->define(
		ResellerClosingAccount::class, function(Faker $faker) {
		return [
			//
			'transaction_type' => 'transfer',
			'container_id' => factory(TransactionsContainer::class)->create()->id,
			'shortage_amount' => 0,
			'amount' => 0,
			'organization_id' => 0,
			'creator_id' => 0,
			'receiver_id' => 0,
			'remaining_accounts_balance' => 0,
			'remaining_amount' => 0,
		];
	}
	);
