<?php
	
	
	namespace App\Components\Observer\InvoiceItems;
	use App\InvoiceItems;
	
	class InvoiceItemsObserver
	{
		/**
		 * Handle the invoice item "created" event.
		 *
		 * @param InvoiceItems $InvoiceItems
		 *
		 * @return void
		 */
		public function created(InvoiceItems $InvoiceItems)
		{
			new  Created($InvoiceItems);
		}
		
		/**
		 * Handle the invoice item "updated" event.
		 *
		 * @param InvoiceItems $InvoiceItems
		 *
		 * @return void
		 */
		public function updated(InvoiceItems $InvoiceItems)
		{
			//
		}
		
		/**
		 * Handle the invoice item "deleted" event.
		 *
		 * @param InvoiceItems $InvoiceItems
		 *
		 * @return void
		 */
		public function deleted(InvoiceItems $InvoiceItems)
		{
			//
		}
		
		/**
		 * Handle the invoice item "restored" event.
		 *
		 * @param InvoiceItems $InvoiceItems
		 *
		 * @return void
		 */
		public function restored(InvoiceItems $InvoiceItems)
		{
			//
		}
		
		/**
		 * Handle the invoice item "force deleted" event.
		 *
		 * @param InvoiceItems $InvoiceItems
		 *
		 * @return void
		 */
		public function forceDeleted(InvoiceItems $InvoiceItems)
		{
			//
		}
	}
