<?php
	
	namespace App\Console\Commands\Tinker;
	
	use App\Models\Transaction;
	use App\Models\User;
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

//			$invoice  = Invoice::find(11092);

//			$transactions = Transaction::where('invoice_id',11092)->get()->toArray();

//			dd($transactions);
//			DB::beginTransaction();
//
			try {
////				dd(1);
				$invoiceItem = InvoiceItems::find(16201);
				$newDiscount = 0;
				$currentDiscount = $invoiceItem->discount;
				$oldTotal = $invoiceItem->total;
				$price = $invoiceItem->price - $currentDiscount;
				$total = $price;
				$invoiceItem->update(
					[
						'discount' => $newDiscount,
						'price' => $price,
						'total' => $price
					]
				);
				dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoiceItem->invoice));


				$costTransaction = Transaction::find(59728);//total_credit_amount
				$costTransaction->update(
					[
						'amount' => $costTransaction->amount - $oldTotal + $total,
						'total_credit_amount' => $costTransaction->account->total_credit_amount - $oldTotal + $total
					]
				);

				$costTransaction->account->update(
					[
						'total_credit_amount' => $costTransaction->account->total_credit_amount - $oldTotal + $total
					]
				);


				$costDiscountTransaction = Transaction::find(59729);//total_debit_amount

				$costDiscountTransaction->update(
					[
						'amount' => $costDiscountTransaction->amount - $currentDiscount,
						'total_debit_amount' => $costDiscountTransaction->account->total_debit_amount - $currentDiscount
					]
				);

				$costDiscountTransaction->account->update(
					[
						'total_debit_amount' => $costDiscountTransaction->account->total_debit_amount - $currentDiscount
					]
				);
				
				$vendors = User::where('is_vendor', true)->get();
				
				foreach($vendors as $vendor) {
					$transactions = Transaction::where([
						['user_id', $vendor->id],
						['account_id',20]
					])->get();
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
					
					echo $vendor->id . " - " . $creditAmount . ':' . $debitAmount .' - ' . $balance . "\n";
					
					$vendor->update(
						[
							'vendor_balance' => $balance
						]
					);
					
				}
				DB::commit();
			} catch(Exception $exception) {
				DB::rollBack();
				throw  $exception;
				
			}
			
			
		}
	}
