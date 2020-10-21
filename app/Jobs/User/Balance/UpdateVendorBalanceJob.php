<?php
	
	namespace App\Jobs\User\Balance;
	
	use App\Models\User;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	
	class UpdateVendorBalanceJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		private $amount, $vendor, $type;
		
		/**
		 * Create a new job instance.
		 *
		 * @param User $vendor
		 * @param $amount
		 * @param string $type
		 */
		public function __construct(User $vendor, $amount, $type = 'increase')
		{
			$this->vendor = $vendor;
			$this->amount = $amount;
			$this->type = $type;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			$balance = $this->vendor->vendor_balance;
			if($this->type == 'increase') {
				$newBalance = (float)$balance + (float)$this->amount;
			} else {
				$newBalance = (float)$balance - (float)$this->amount;
			}
			$this->vendor->update(
				[
					'vendor_balance' => $newBalance
				]
			);
		}
	}
