<?php
	
	namespace App\Console\Commands\Tinker;
	
	use App\Jobs\User\Balance\UpdateVendorBalanceJob;
	use App\Models\Account;
	use App\Models\Manager;
	use App\Models\Transaction;
	use App\Models\TransactionsContainer;
	use Carbon\Carbon;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\DB;
	
	class AccountTinkerCommand extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:account_tinker';
		
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
			$accounts = Account::where('parent_id', '!=', 0)->whereNotIn('id', [13, 12, 20, 24])->get();
			$createdAt = Carbon::parse('30-09-2020');
			$baseAccount = Account::find(24);
			
			foreach($accounts as $account) {
				$oldAccount = DB::connection('data_source')->table('accounts')->find($account->id);
				$oldAccountStatic = DB::connection('data_source')->table('account_statistics')->where('account_id', $account->id)->first();
				if($oldAccount && $oldAccountStatic) {
					$oldBalance = $this->getOldBalance($account, $oldAccountStatic);
					
					$transactions = $account->transactions()->whereDate('created_at', '>', $createdAt)->get();
					
					$balanceAfterUpdate = $this->getTransactionsBalance($account, $transactions);
					
					$balanceInUpdateMoment = $this->getNewBalance($account, $balanceAfterUpdate);
					
					echo "new system: [" . $account->getOriginal('name') . "    balance: " . $balanceInUpdateMoment . "] \t\t\t old system [" . $account->name . "   balance:" . $oldBalance . " ]\n";
					
					
					$variation = (float)$balanceInUpdateMoment - $oldBalance;
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
					
					if($variation != 0) {
						if($account->type == 'credit') {
							if($variation > 0) {
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'credit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $baseAccount->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
								
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'debit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $account->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
								
								
							} else  {
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'debit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $baseAccount->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
								
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'credit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $account->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
							}
						} else {
							if($variation > 0) {
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'debit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $baseAccount->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
								
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'credit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $account->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
								
								
							}else {
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'credit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $baseAccount->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
								
								$transactionData = $transactionInitData;
								$transactionData['amount'] = $amount;
								$transactionData['type'] = 'debit';
								$transactionData['description'] = $description;
								$transactionData['is_manual'] = true;
								$transactionData['account_id'] = $account->id;
								$transactionData['created_at'] = $createdAt;
								$entity->transactions()->create($transactionData);
							}
						}
					}
					
					
				}
				
			}
			
		}
		
		public function getOldBalance($account, $static)
		{
			if($account->type == 'credit')
				return $static->credit_amount - $static->debit_amount;
			
			return $static->debit_amount - $static->credit_amount;
		}
		
		public function getTransactionsBalance($account, $transactions)
		{
			
			$creditAmount = 0;
			$debitAmount = 0;
			foreach($transactions as $transaction) {
				if($transaction->type == 'debit') {
					$debitAmount += (float)$transaction->amount;
				} else {
					$creditAmount += (float)$transaction->amount;
				}
			}
			
			if($account->type == 'credit')
				return $creditAmount - $debitAmount;
			
			return $debitAmount - $creditAmount;
			
		}
		
		public function getNewBalance($account, $balanceAfterUpdate = 0)
		{
			if($account->type == 'credit')
				$balance = $account->total_credit_amount - $account->total_debit_amount;
			else
				$balance = $account->total_debit_amount - $account->total_credit_amount;
			
			return $balance - $balanceAfterUpdate;
		}
	}
