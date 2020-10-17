<?php
	
	namespace App\Console\Commands;
	
	use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
	use App\Models\Account;
	use Illuminate\Console\Command;
	
	class PlayGroundCommand extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:play_command';
		
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
//        $account = Account::find(3);
//        $snapshot = $account->snapshots()->whereDate('created_at', '2020-10-13')->first();
//
//
//        dd($snapshot);
			
			$invoice = Invoice::find(11092);

//			$transactions = Transaction::where('invoice_id',11092)->get()->toArray();

//			dd($transactions);
//			DB::beginTransaction();
//
//				dd(1);
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
			
			
		}
	}
