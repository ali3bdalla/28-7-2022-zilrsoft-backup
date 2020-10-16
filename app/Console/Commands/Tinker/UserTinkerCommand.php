<?php
	
	namespace App\Console\Commands\Tinker;
	
	use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
	use App\Jobs\User\Balance\UpdateVendorBalanceJob;
	use App\Models\Account;
	use App\Models\InvoiceItems;
	use App\Models\Manager;
	use App\Models\Transaction;
	use App\Models\TransactionsContainer;
	use App\Models\User;
	use Carbon\Carbon;
	use Exception;
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
			$createdAt = Carbon::parse('30-09-2020');
			foreach($vendors as $vendor) {
				$oldVendor = DB::connection('data_source')->table('users')->find($vendor->id);
				if($oldVendor) {
					$transactions = Transaction::where(
						[
							['user_id', $vendor->id],
							['account_id', 20]
						]
					)->whereDate('created_at', '<=', $createdAt)->get();
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

//				" - " . $creditAmount . ':' . $debitAmount . ' - '
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


//				$vendor->update(
//					[
//						'vendor_balance' => $balance
//					]
//				);
				
			}


//			$invoice  = Invoice::find(11092);

//			$transactions = Transaction::where('invoice_id',11092)->get()->toArray();

//			dd($transactions);
//			DB::beginTransaction();
//
//			try {
//////				dd(1);
//				$invoiceItem = InvoiceItems::find(16201);
//				$newDiscount = 0;
//				$currentDiscount = $invoiceItem->discount;
//				$oldTotal = $invoiceItem->total;
//				$price = $invoiceItem->price - $currentDiscount;
//				$total = $price;
//				$invoiceItem->update(
//					[
//						'discount' => $newDiscount,
//						'price' => $price,
//						'total' => $price
//					]
//				);
//				dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoiceItem->invoice));
//
//
//				$costTransaction = Transaction::find(59728);//total_credit_amount
//				$costTransaction->update(
//					[
//						'amount' => $costTransaction->amount - $oldTotal + $total,
//						'total_credit_amount' => $costTransaction->account->total_credit_amount - $oldTotal + $total
//					]
//				);
//
//				$costTransaction->account->update(
//					[
//						'total_credit_amount' => $costTransaction->account->total_credit_amount - $oldTotal + $total
//					]
//				);
//
//
//				$costDiscountTransaction = Transaction::find(59729);//total_debit_amount
//
//				$costDiscountTransaction->update(
//					[
//						'amount' => $costDiscountTransaction->amount - $currentDiscount,
//						'total_debit_amount' => $costDiscountTransaction->account->total_debit_amount - $currentDiscount
//					]
//				);
//
//				$costDiscountTransaction->account->update(
//					[
//						'total_debit_amount' => $costDiscountTransaction->account->total_debit_amount - $currentDiscount
//					]
//				);
//
//				$vendors = User::where('is_vendor', true)->get();
//
//				foreach($vendors as $vendor) {
//					$transactions = Transaction::where(
//						[
//							['user_id', $vendor->id],
//							['account_id', 20]
//						]
//					)->get();
//					$creditAmount = 0;
//					$debitAmount = 0;
//
//					foreach($transactions as $transaction) {
//						if($transaction->type == 'debit') {
//							$debitAmount += (float)$transaction->amount;
//						} else {
//							$creditAmount += (float)$transaction->amount;
//						}
//					}
//					$balance = $creditAmount - $debitAmount;
//
//					echo $vendor->id . " - " . $creditAmount . ':' . $debitAmount . ' - ' . $balance . "\n";
//
//					$vendor->update(
//						[
//							'vendor_balance' => $balance
//						]
//					);
//
//				}
//				DB::commit();
//			} catch(Exception $exception) {
//				DB::rollBack();
//				throw  $exception;
//
//			}
//
//
		}
	}
