<?php
	
	namespace Tests\Feature;
	
	use App\Entry;
	use App\PurchaseInvoice;
	use Tests\TestCase;
	
	class EntryTest extends TestCase
	{
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testToCreateBeginningInventoryEntry()
		{
			$beginning_inventory = PurchaseInvoice::where('invoice_type','beginning_inventory')->inRandomOrder()->first();
			$entry = $beginning_inventory->invoice->create_invoice_entry($beginning_inventory);
			$this->assertInstanceOf(Entry::class,$entry);
			
		}
	}
