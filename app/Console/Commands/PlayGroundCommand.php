<?php
	
	namespace App\Console\Commands;
	
	use App\Models\Category;
	use App\Models\CategoryFilters;
	use App\Models\CategoryFilterValues;
	use App\Models\Item;
	use App\Models\ItemFilters;
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

//        $accounts = Category::all();
//
//        foreach ($accounts as $account) {
//            if ($account->parent) {
//                $account->parent->updateHashMap();
//            }
//            $account->updateHashMap();
//		}
			
			
			CategoryFilterValues::truncate();
			
			$itemsFilters = ItemFilters::all();
			
			foreach($itemsFilters as $itemFilter) {
				
				$item = $itemFilter->item()->withoutGlobalScope('online')->first();
				if($item) {
					$exists = $item->category->filtersValues()->where(
						[
							['filter_id', $itemFilter->filter_id],
							['value_id', $itemFilter->filter_value],
						]
					)->first();
					
					if(!$exists) {
						$item->category->filtersValues()->create(
							[
								'filter_id' => $itemFilter->filter_id,
								'value_id' => $itemFilter->filter_value
							]
						);
					}
				}
				
			}
			
			// dd($accounts );
//        $account = Account::find(3);
			//        $snapshot = $account->snapshots()->whereDate('created_at', '2020-10-13')->first();
			//
			//
			//        dd($snapshot);

//            $invoice = Invoice::find(11092);

//            $transactions = Transaction::where('invoice_id',11092)->get()->toArray();

//            dd($transactions);
			//            DB::beginTransaction();
			//
			//                dd(1);
			//            $invoiceItem = InvoiceItems::find(16201);
			//            $newDiscount = 0;
			//            $currentDiscount = $invoiceItem->discount;
			//            $oldTotal = $invoiceItem->total;
			//            $price = $invoiceItem->price - $currentDiscount;
			//            $total = $price;
			//            $invoiceItem->update(
			//                [
			//                    'discount' => $newDiscount,
			//                    'price' => $price,
			//                    'total' => $price
			//                ]
			//            );
			//            dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoiceItem->invoice));
			//            $costTransaction = Transaction::find(59728);//total_credit_amount
			//            $costTransaction->update(
			//                [
			//                    'amount' => $costTransaction->amount - $oldTotal + $total,
			//                    'total_credit_amount' => $costTransaction->account->total_credit_amount - $oldTotal + $total
			//                ]
			//            );
			//
			//            $costTransaction->account->update(
			//                [
			//                    'total_credit_amount' => $costTransaction->account->total_credit_amount - $oldTotal + $total
			//                ]
			//            );
			//
			//
			//            $costDiscountTransaction = Transaction::find(59729);//total_debit_amount
			//
			//            $costDiscountTransaction->update(
			//                [
			//                    'amount' => $costDiscountTransaction->amount - $currentDiscount,
			//                    'total_debit_amount' => $costDiscountTransaction->account->total_debit_amount - $currentDiscount
			//                ]
			//            );
			//
			//            $costDiscountTransaction->account->update(
			//                [
			//                    'total_debit_amount' => $costDiscountTransaction->account->total_debit_amount - $currentDiscount
			//                ]
			//            );
			//

//            $account=  Account::find(21);
			//            $startDate = Carbon::parse('01-01-2020');
			//            $endDate = Carbon::parse('30-09-2020');
			//            $totalCredit = $account->transactions()->where('type', 'credit')->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
			////            $totalDebit = $account->transactions()->where('type', 'debit')->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
			//
			//            dd($totalCredit);

//            $transactions = Transaction::orderBy('created_at', 'asc')->get();
			//
			//            Account::where('id', '!=', 0)->update(
			//                [
			//                    'total_credit_amount' => 0,
			//                    'total_debit_amount' => 0,
			//                ]
			//            );
			//            AccountSnapshot::where('id', '!=', 0)->update([
			//                'debit_amount' => 0,
			//                'credit_amount' => 0,
			//            ]);
			//            foreach($transactions as $transaction) {
			//                echo "{$transaction->id}\n";
			//                dispatch(new UpdateAccountBalanceJob($transaction));
			//            }

//            User::where('id', '!=', 0)->update(
			//                [
			//
			//                    'balance' => 0,
			//                    'vendor_balance' => 0,
			//
			//                ]
			//            );
			//
			//            $clients = Account::find(12);
			//            $vendors = Account::find(20);
			//
			//            $users = User::all();
			//
			//            foreach($users as $user) {
			//                $vendorTransactions = $vendors->transactions()->where('user_id', $user->id)->get();
			//                $clientTransactions = $clients->transactions()->where('user_id', $user->id)->get();
			//                $vendorBalance = 0;
			//                $clientBalance = 0;
			//
			//                foreach($vendorTransactions as $transaction) {
			//                    if($transaction->type == 'credit') {
			//                        $vendorBalance += $transaction->amount;
			//                    } else {
			//                        $vendorBalance -= $transaction->amount;
			//
			//                    }
			//                }
			//
			//
			//                foreach($clientTransactions as $transaction) {
			//                    if($transaction->type == 'debit') {
			//                        $clientBalance += $transaction->amount;
			//                    } else {
			//                        $clientBalance -= $transaction->amount;
			//
			//                    }
			//                }
			//
			//
			//                $user->update(
			//                    [
			//                        'balance' => $clientBalance,
			//                        'vendor_balance' => $vendorBalance,
			//                    ]
			//                );
			//
			//            }

//            $debitAmount = Transaction::where('type', 'debit')->sum('amount');
			//            $accountsDebitAmount = Account::sum('total_debit_amount');
			//            $creditAmount = Transaction::where('type', 'credit')->sum('amount');
			//            $accountCreditAmount = Account::sum('total_credit_amount');
			//            dd($debitAmount,$accountsDebitAmount,$creditAmount,$accountCreditAmount);

//            $createdAt = Carbon::parse('30-9-2020');
			//
			//
			//            $containers = TransactionsContainer::where('created_at', '>', $createdAt)->get();
			//
			//            foreach($containers as $container) {
			//                $debitAmount = $container->transactions()->where('type', 'debit')->sum('amount');
			//                $creditAmount = $container->transactions()->where('type', 'credit')->sum('amount');
			//
			//
			//                $variation = abs($debitAmount - $creditAmount);
			//                echo $container->id . "\t invoice_id {$container->invoice_id} \t  variation: {$variation}\n";
			//
			//
			//////                $def = (float)($debitAmount - $creditAmount);
			////                if(roundMoney($debitAmount) != roundMoney($creditAmount)) {
			////                    dd($container->id, $debitAmount - $creditAmount);
			////                }
			//            }
			
		}
	}
