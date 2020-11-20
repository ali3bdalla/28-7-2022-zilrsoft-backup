<?php
	
	namespace App\Providers;
	
	use App\Events\Models\Account\AccountCreated;
	use App\Events\Models\Account\AccountDeleted;
	use App\Events\Models\Account\AccountUpdated;
	use App\Events\Models\Category\CategoryCreated;
	use App\Events\Models\Transaction\TransactionCreated;
	use App\Events\Order\OrderCreatedEvent;
	use App\Listeners\Models\Account\UpdateAccountDetailsListener;
	use App\Listeners\Models\Category\UpdateCategoryDetailsListener;
	use App\Listeners\Models\Transaction\UpdateTransactionDetailsListener;
	use App\Listeners\Order\SendOrderToClientWhatsappListener;
	use App\Listeners\Order\SendPaymentInstructionToClientWhatsappListener;
	use App\Models\Account;
	use Illuminate\Auth\Events\Registered;
	use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
	use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
	
	class EventServiceProvider extends ServiceProvider
	{
		
		/**
		 * The event listener mappings for the application.
		 *
		 * @var array
		 */
		protected $listen = [
			Registered::class => [
				SendEmailVerificationNotification::class,
			],
			
			'Illuminate\Auth\Events\Verified' => [
				'App\Listeners\LogVerifiedUser',
			],
			'App\Events\UserCreatedEvent' => [
				'App\Listeners\UserCreatedListener'
			],
			
			AccountCreated::class => [
				UpdateAccountDetailsListener::class
			],
			AccountUpdated::class => [
				UpdateAccountDetailsListener::class
			],
			AccountDeleted::class => [
//				UpdateAccountDetailsListener::class
			],
			
			TransactionCreated::class => [
				UpdateTransactionDetailsListener::class
			],
			
			CategoryCreated::class => [
				UpdateCategoryDetailsListener::class
			],
			OrderCreatedEvent::class => [
				SendOrderToClientWhatsappListener::class,
				SendPaymentInstructionToClientWhatsappListener::class
			]
		
		];
		
		/**
		 * Register any events for your application.
		 *
		 * @return void
		 */
		public function boot()
		{
			parent::boot();
			
		}
	}
