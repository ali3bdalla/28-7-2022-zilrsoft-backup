<?php
	
	namespace App\Listeners\Order;
	
	use AliAbdalla\Whatsapp\Whatsapp;
	use Carbon\Carbon;
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
			dd($event->order->user->phone_number);
			Whatsapp::sendFile(
				$event->path, [$event->order->user->phone_number], $event->order->id
			);
			Whatsapp::sendMessage(
				$message, [$event->order->user->phone_number]
			);
		}
		
		
	}
