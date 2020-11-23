<?php
	
	namespace App\Jobs\Accounting\Entity;
	
	use App\Models\Account;
	use App\Models\Transaction;
	use Carbon\Carbon;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class UpdateAccountBalanceJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		private $transaction;
		/**
		 * @var bool
		 */
		private $increase;
		
		/**
		 * Create a new job instance.
		 *
		 * @param Transaction $transaction
		 * @param bool $increase
		 */
		public function __construct(Transaction $transaction, $increase = true)
		{
			//
			$this->transaction = $transaction;
			$this->increase = $increase;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			// grab account
			$account = Account::where('id',$this->transaction->account_id)->withTrashed()->first();
			$createdAt = Carbon::parse($this->transaction->created_at);
			
			$snapshot = $account->snapshots()->whereDate('created_at', $createdAt)->first();
			
			if($snapshot == null) {
				$snapshot = $account->snapshots()->create(
					[
						'organization_id' => $account->organization_id,
						'created_at' => $createdAt
					]
				);
				
			}
			
			$snapshotDebitAmount = $snapshot->debit_amount;
			$snapshotCreditAmount = $snapshot->credit_amount;
			
			if($this->transaction->type == 'credit') {
				$totalDebitAmount = $account->total_debit_amount;
				
				if($this->increase) {
					
					$totalCreditAmount = (float)$account->total_credit_amount + (float)$this->transaction->amount;
					
					$snapshotCreditAmount = (float)$snapshotCreditAmount + (float)$this->transaction->amount;
				} else {
					$totalCreditAmount = (float)$account->total_credit_amount - (float)$this->transaction->amount;
					
					$snapshotCreditAmount = (float)$snapshotCreditAmount - (float)$this->transaction->amount;
				}
				
				$snapshot->update(
					[
						'credit_amount' => $snapshotCreditAmount,
					]
				);
				$account->update(
					[
						'total_credit_amount' => $totalCreditAmount,
					]
				);
				
			} else {
				$totalCreditAmount = $account->total_credit_amount;
				
				if($this->increase) {
					$totalDebitAmount = (float)$account->total_debit_amount + (float)$this->transaction->amount;
					$snapshotDebitAmount = (float)$snapshotDebitAmount + (float)$this->transaction->amount;
				} else {
					$totalDebitAmount = (float)$account->total_debit_amount - (float)$this->transaction->amount;
					$snapshotDebitAmount = (float)$snapshotDebitAmount - (float)$this->transaction->amount;
				}
				
				$snapshot->update(
					[
						'debit_amount' => $snapshotDebitAmount,
					]
				);
				$account->update(
					[
						'total_debit_amount' => $totalDebitAmount,
					]
				);
			}
			
			$this->transaction->update(
				[
					'total_debit_amount' => $totalDebitAmount,
					'total_credit_amount' => $totalCreditAmount,
				]
			);
		}
	}
