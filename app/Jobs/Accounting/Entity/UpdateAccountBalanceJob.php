<?php
	
	namespace App\Jobs\Accounting\Entity;
	
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
		 * Create a new job instance.
		 *
		 * @param Transaction $transaction
		 */
		public function __construct(Transaction $transaction)
		{
			//
			$this->transaction = $transaction;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			// grab account
			$account = $this->transaction->account;
			
			
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
				
				// To Do Sum
				$totalCreditAmount = (float)$account->total_credit_amount + (float)$this->transaction->amount;
				$totalDebitAmount = $account->total_debit_amount;
				
				$snapshotCreditAmount = (float)$snapshotCreditAmount + (float)$this->transaction->amount;
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
				$totalDebitAmount = $account->total_debit_amount + (float)$this->transaction->amount;
				$totalCreditAmount = $account->total_credit_amount;
				$snapshotDebitAmount += (float)$this->transaction->amount;
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
