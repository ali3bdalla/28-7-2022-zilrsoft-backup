<?php
	
	namespace App\Console\Commands\Tinker;
	
	use App\Jobs\User\Balance\UpdateVendorBalanceJob;
	use App\Models\Account;
	use App\Models\Manager;
	use App\Models\Transaction;
	use App\Models\TransactionsContainer;
	use App\Models\User;
	use Carbon\Carbon;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\DB;
	
	class UserTinkerCommand extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:user_tinker';
		
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
			
			
			
			
			$vendors = User::where([['is_vendor', true], ['is_system_user', 'false']])->get();
			$lastDate = Carbon::parse('01-10-2020');
			$createdAt = Carbon::parse('30-09-2020');
			foreach($vendors as $vendor) {
				$oldVendor = DB::connection('data_source')->table('users')->find($vendor->id);
				if($oldVendor) {
					$transactions = Transaction::where(
						[
							['user_id', $vendor->id],
							['account_id', 20]
						]
					)->whereDate('created_at', '<', $lastDate)->get();
					$creditAmount = 0;
					$debitAmount = 0;
					foreach($transactions as $transaction) {
						if($transaction->type == 'debit') {
							$debitAmount += (float)$transaction->amount;
						} else {
							$creditAmount += (float)$transaction->amount;
						}
					}
					
					$balance = $creditAmount - $debitAmount;
					
					echo "new system: [" . $vendor->getOriginal('name') . "    balance: " . $balance . "] \t\t\t old system [" . $oldVendor->name . "   balance:" . $oldVendor->vendor_balance . " ]\n";
//
					
					$variation = (float)$balance - $oldVendor->vendor_balance;
					$loggedUser = Manager::find(1);
					$amount = abs($variation);
					$description = 'تسويات تعديل  رصيد';
					$entity = new TransactionsContainer(
						[
							'organization_id' => $loggedUser->organization_id,
							'creator_id' => $loggedUser->id,
							'description' => $description,
							'amount' => $amount,
							'created_at' => $createdAt,
						
						]
					);
					$entity->save();
					$transactionInitData = [
						'creator_id' => $loggedUser->id,
						'organization_id' => $loggedUser->organization_id,
					];
					
					if($variation > 0) {
						
						$account = Account::find(24);
						$transactionData = $transactionInitData;
						$transactionData['amount'] = $amount;
						$transactionData['type'] = 'credit';
						$transactionData['description'] = $description;
						$transactionData['is_manual'] = true;
						$transactionData['account_id'] = $account->id;
						$transactionData['created_at'] = $createdAt;
						$entity->transactions()->create($transactionData);
						
						$stockAccount = Account::find(20);
						$transactionData = $transactionInitData;
						$transactionData['amount'] = $amount;
						$transactionData['type'] = 'debit';
						$transactionData['description'] = $description;
						$transactionData['is_manual'] = true;
						$transactionData['account_id'] = $stockAccount->id;
						$transactionData['created_at'] = $createdAt;
						$transactionData['user_id'] = $vendor->id;
						$entity->transactions()->create($transactionData);
						dispatch(new UpdateVendorBalanceJob($vendor, $amount, 'decrease'));
						
						
					} else if($variation < 0) {
						$account = Account::find(24);
						$transactionData = $transactionInitData;
						$transactionData['amount'] = $amount;
						$transactionData['type'] = 'debit';
						$transactionData['description'] = $description;
						$transactionData['is_manual'] = true;
						$transactionData['account_id'] = $account->id;
						$transactionData['created_at'] = $createdAt;
						$entity->transactions()->create($transactionData);
						
						$stockAccount = Account::find(20);
						$transactionData = $transactionInitData;
						$transactionData['amount'] = $amount;
						$transactionData['type'] = 'credit';
						$transactionData['description'] = $description;
						$transactionData['is_manual'] = true;
						$transactionData['account_id'] = $stockAccount->id;
						$transactionData['created_at'] = $createdAt;
						$transactionData['user_id'] = $vendor->id;
						$entity->transactions()->create($transactionData);
						dispatch(new UpdateVendorBalanceJob($vendor, $amount, 'increase'));
					}
					
					
				}
				
			}
		}
	}
