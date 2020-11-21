<?php
	
	namespace App\Console\Commands;
	
	use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
	use App\Models\InvoiceItems;
	use App\Models\OrderItemQtyHolder;
	use Carbon\Carbon;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\Storage;
	
	class DestroyItemHoldQtyCommand extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:DestroyItemHoldQtyCommand';
		
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
			$holdQty = OrderItemQtyHolder::where([['status', 'hold']])->whereDate('hold_destroy_at', '>=', Carbon::now())->get();
//			Storage::put(Carbon::now()->toDateTimeString() . $holdQty , 'sdjkflds');
			foreach($holdQty as $holdQty) {
				UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
				$holdQty->update(
					[
						'status' => 'destroyed'
					]
				);
				
				$holdQty->order()->update(
					[
						'status' => 'canceled'
					]
				);
			}
			
		}
	}
