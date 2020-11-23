<?php
	
	namespace App\Listeners\Order;
	
	use AliAbdalla\Whatsapp\Whatsapp;
	use Carbon\Carbon;
	use PDF;
	
	
	class SendOrderToClientViaWhatsappListener
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
			
			$phoneNumber = $event->order->user->phone_number;
			$message = view(
				'whatsapp.order_details', [
					'client' => $event->client,
					'order' => $event->order,
					'invoice' => $event->invoice,
					'deadline' => Carbon::now()->addMinutes(30)->format('H:i')
				]
			)->toHtml();
			Whatsapp::sendMessage(
				$message, [$phoneNumber]
			);
//			dd($event->path);
			Whatsapp::sendFile(
				$event->path, [$phoneNumber], $event->invoice->id
			);
			
		}
		
		
	}
