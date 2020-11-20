<?php
	
	namespace App\Listeners\Order;
	
	use AliAbdalla\Whatsapp\Whatsapp;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\App;
	use Illuminate\Support\Facades\Storage;
	use PDF;
	
	
	class SendOrderToClientWhatsappListener
	{
		/**
		 * Create the event listener.
		 *
		 * @return void
		 */
		public function __construct()
		{
			//
		}
		
		/**
		 * Handle the event.
		 *
		 * @param object $event
		 * @return void
		 */
		public function handle($event)
		{
		
			$message = view(
				'whatsapp.order_details', [
					'client' => $event->client,
					'order' => $event->order,
					'invoice' => $event->invoice,
					'deadline' => Carbon::now()->addMinutes(30)->format('H:ia')
				]
			)->toHtml();
			
			Whatsapp::sendFile(
				$event->path, ['966504956211'],  $event->order->id
			);
			Whatsapp::sendMessage(
				$message, ['966504956211']
			);
		}
		
		
		
		
	}
