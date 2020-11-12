<?php
	
	namespace App\Console\Commands;
	
	use App\Events\Order\OrderIssuedEvent;
	use App\Events\Order\OrderPendingEvent;
	use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
	use App\Models\Account;
	use App\Models\AccountSnapshot;
	use App\Models\Category;
	use App\Models\CategoryFilters;
	use App\Models\CategoryFilterValues;
	use App\Models\Item;
	use App\Models\ItemFilters;
	use App\Models\Order;
	use App\Models\Transaction;
	use App\Models\TransactionsContainer;
	use Carbon\Carbon;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\DB;
	
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
			
//			$accounts = Account::withTrashed()->get();
//
//			foreach($accounts as $account) {
//				$account->updateHashMap();
//			}
			$tables = DB::select("SELECT table_schema,table_name, table_catalog FROM information_schema.tables WHERE  table_schema = 'zilrsoft_production' ORDER BY table_name;");
			
			foreach($tables as $table) {
				if(!in_array($table->table_name,['migrations',"model_has_permissions","model_has_roles","password_resets","role_has_permissions"]))
				{
					DB::statement("ALTER TABLE {$table->table_name} ALTER updated_at SET DATA TYPE timestamp(0) without time zone;");
					DB::statement("ALTER TABLE {$table->table_name} ALTER created_at SET DATA TYPE timestamp(0) without time zone;");
				}
//				echo $table->table_name;
			}
//			dd($tables);

//			$startDate = Carbon::parse('11/5/2020');
//			$transactions = Transaction::whereDate('created_at', '>=',$startDate)->get();
//			AccountSnapshot::whereDate('created_at', '>=',$startDate)->update(
//				[
//					'debit_amount' => 0,
//					'credit_amount' => 0,
//				]
//			);
//
//			foreach($transactions as $transaction) {
//				$account = Account::withTrashed()->where('id', $transaction->account_id)->first();
//
//				if($transaction->type == 'credit') {
//					$transaction->account()->update(
//						[
//							'total_credit_amount' => (float)$account->total_credit_amount - (float)$transaction->amount,
//						]
//					);
//				} else {
//					$transaction->account()->update(
//						[
//							'total_debit_amount' => (float)$account->total_debit_amount - (float)$transaction->amount,
//						]
//					);
//				}
//
//
//				dispatch(new UpdateAccountBalanceJob($transaction, true));
//
//			}
//
//
//			$accounts = Account::withTrashed()->get();
//
//			foreach($accounts as $account) {
//				$debit = $account->snapshots()->sum('debit_amount');
//				$credit = $account->snapshots()->sum('credit_amount');
//
//				$account->update(
//					[
//						'total_debit_amount' => $debit,
//						'total_credit_amount' => $credit,
//					]
//				);
//			}
			
		}
		
		
	}
