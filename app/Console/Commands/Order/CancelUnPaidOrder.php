<?php
	
	namespace App\Console\Commands\Order;
	
	use AliAbdalla\Whatsapp\Whatsapp;
	use App\Events\Order\OrderCanceledEvent;
	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Jobs\Order\CancelOrderJob;
	use App\Models\InvoiceItems;
	use App\Models\Order;
	use App\Models\OrderItemQtyHolder;
	use Carbon\Carbon;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\Storage;
	
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
			
//			dd($orders);
			foreach($orders as $order) {
			
				dispatch(new CancelOrderJob($order));
				
			}
			
		}
		
	
	}
