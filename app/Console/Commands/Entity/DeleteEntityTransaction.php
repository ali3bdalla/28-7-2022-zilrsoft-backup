<?php
	
	namespace App\Console\Commands\Entity;
	
	use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
	use App\Jobs\User\Balance\UpdateClientBalanceJob;
	use App\Jobs\User\Balance\UpdateVendorBalanceJob;
	use App\Models\Transaction;
	use App\Models\TransactionsContainer;
	use Illuminate\Console\Command;
	
	class DeleteEntityTransaction extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:DeleteEntityTransaction {entityId}';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Command description';
		
		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle()
		{
			$entityId = $this->argument('entityId');
			
			if($entityId) {
				$transaction = Transaction::find($entityId);
				if($transaction) {
					if($transaction->account->slug == 'vendors') {
						dispatch(new UpdateVendorBalanceJob($transaction->user, $transaction->amount, 'decrease'));
					}
					if($transaction->account->slug == 'clients') {
						dispatch(new UpdateClientBalanceJob($transaction->user, $transaction->amount, 'decrease'));
					}
					dispatch(new UpdateAccountBalanceJob($transaction, false));
					$transaction->forceDelete();
				}
				
			}
			
			
		}
	}
