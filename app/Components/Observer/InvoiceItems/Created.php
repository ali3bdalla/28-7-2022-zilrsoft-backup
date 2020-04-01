<?php
	
	
	namespace App\Components\Observer\InvoiceItems;
	
	
	use App\InvoiceItems;
	use Illuminate\Support\Facades\DB;
	
	class Created
	{
		private $invoiceItem = null;
		private $item_statistics = null;
		
		
		private $profit = 0;
		public function __construct(InvoiceItems $invoiceItem)
		{
			$this->invoiceItem = $invoiceItem;
			$this->getItemStatisticsRow();
			if ($this->invoiceItem->invoice_type == 'sale')
				$this->sales();
			
			if ($this->invoiceItem->invoice_type == 'purchase')
				$this->purchase();
			
			if ($this->invoiceItem->invoice_type == 'r_purchase')
				$this->returnPurchase();
			
			if ($this->invoiceItem->invoice_type == 'r_sale')
				$this->returnSales();
			
		}
		
		private function getItemStatisticsRow()
		{
			$this->item_statistics = $this->invoiceItem->item->statistics;
			if (!$this->item_statistics)
				$this->item_statistics = $this->invoiceItem->item->statistics()->create();
			
			$this->profit = $this->invoiceItem->total - ($this->invoiceItem->cost * $this->invoiceItem->qty);
			
		}
		
		private function sales()
		{
			
			
			
			$this->item_statistics->update([
				'sales_count' => DB::raw("sales_count + 1"),
				'profits' => DB::raw("profits + $this->profit"),
			]);
			
			
		}
		
		private function returnSales()
		{
			
			$this->item_statistics->update([
				'return_sales_count' => DB::raw("return_sales_count + 1"),
				'profits' => DB::raw("profits - $this->profit"),
			]);
			
			
		}
		
		
		private function purchase()
		{
			
			$this->item_statistics->update([
				'purchase_count' => DB::raw("purchase_count + 1")
			]);
			
			
		}
		
		
		private function returnPurchase()
		{
			
			$this->item_statistics->update([
				'return_purchase_count' => DB::raw("return_purchase_count + 1")
			]);
			
			
		}
	}