<?php
	
	
	namespace App\Attributes;
	
	use Illuminate\Support\Facades\DB;
	
	trait  InvoiceItemAttributes
	{
		
		public function addToReturnedQty($new_returned_qty)
		{
			return $this->update([
				'r_qty' => DB::raw("r_qty + $new_returned_qty")
			]);
		}
		
		public function updateStock()
		{
			
			$cost = $this->item->cost;
			$current_stock = $cost * $this->item->available_qty;
			$result = [];
			$result['final_stock_cost'] = $cost;
			if (in_array($this->invoice->invoice_type,['purchase','beginning_inventory'])){
				$result = $this->item->handlePurchaseHistory($this,$current_stock,$this->item->available_qty);
			}else if ($this->invoice->invoice_type == 'sale'){
				$result = $this->item->handleSaleHistory($this,$cost,$current_stock,$this->item->available_qty);
			}else if ($this->invoice->invoice_type == 'r_sale'){
				$result = $this->item->handleReturnSaleHistory($this,$current_stock,$cost,$this->item->available_qty);
			}else if ($this->invoice->invoice_type == 'r_purchase'){
				$result = $this->item->handleReturnPurchaseHistory($this,$cost,$current_stock,$this->item->available_qty);
			}
			
			
			
			$this->item->update([
				'cost' => $result['final_stock_cost']
			]);
		}
		
		public function getDescriptionAttribute()
		{
			$description = '';
			if (app()->isLocale('ar')){
				if ($this->invoice_type == 'purchase')
					$description = ' شراء';
				
				if ($this->invoice_type == 'sale')
					$description = 'بيع';
				
				if ($this->invoice_type == 'beginning_inventory')
					$description = 'اول مدة';
				
				if ($this->invoice_type == 'r_sale')
					$description = 'مرتجع بيع';
				
				if ($this->invoice_type == 'r_purchase')
					$description = 'مرتجع شراء';
				
			}else{
				if ($this->invoice_type == 'sale')
					$description = 'sale';
				
				if ($this->invoice_type == 'purchase')
					$description = 'purchase';
				
				if ($this->invoice_type == 'beginning_inventory')
					$description = 'beginning inventory';
				
				if ($this->invoice_type == 'r_sale')
					$description = 'return sale';
				
				
				if ($this->invoice_type == 'r_purchase')
					$description = 'return purchase';
				
			}
			
			
			return $description;
		}
		
		public function getUrlsAttribute()
		{
			$urls = [];
			if (in_array($this->invoice_type,['sale','r_sale']))
				$url['invoice_url'] = route('management.sales.show',$this->invoice->sale->id);
			else
				$url['invoice_url'] = route('management.purchases.show',$this->invoice->purchase->id);
			
			$url['invoice_title'] = $this->invoice->title;
			
			return $url;
		}
		
		public function getPriceAttribute($value)
		{
			
			return money_format('%.2n',$value);
		}
		
		public function getTotalAttribute($value)
		{
			return money_format('%.2n',$value);
		}
		
		public function getDiscountAttribute($value)
		{
			
			return money_format('%.2n',$value);
		}
		
		public function getTaxAttribute($value)
		{
			return money_format('%.2n',$value);
		}
		
		public function getNetAttribute($value)
		{
			
			return money_format('%.2n',$value);
		}
		
		public function getSubtotalAttribute($value)
		{
			
			return money_format('%i',$value);
		}
		
	}
