<?php
	
	namespace App\Console\Commands\Order;
	
	use App\Events\Order\OrderCanceledEvent;
	use App\Jobs\Order\CancelOrderJob;
	use App\Models\Order;
	use Carbon\Carbon;
	use Illuminate\Console\Command;
	
	class CancelUnPaidOrder extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:cancelUnPaidOrder';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'This Command Is used to cancel Un Paid Order after it time finish';
		
		
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
			$orders = Order::where('status', 'issued')->whereDate('auto_cancel_at', '<=', Carbon::now())->whereTime('auto_cancel_at', '<=', Carbon::now())->get();
			
			foreach($orders as $order) {
				dispatch(new CancelOrderJob($order));
			}
			
		}
		
		
	}
