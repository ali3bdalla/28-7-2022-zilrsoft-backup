<?php
	
	namespace App\Attributes;
	
	use Illuminate\Support\Facades\Storage;
	
	trait InvoiceAttributes
	{
		
		public function getUserIdAttribute()
		{
			if (in_array($this->invoice_type,['sale','r_sale'])){
				return !empty($this->sale) ?  $this->sale->client_id : 0;
			}
			
			
			return !empty($this->purchase) ?  $this->purchase->vendor_id : 0 ;
		}
		
		public function getNameAttribute()
		{
			return $this->purchase()->prefix;
		}
		
		public function setCreationStatusAs($status)
		{
			if (in_array($status,['paid','credit']))
				$this->update(['current_status' => $status,'issued_status' => $status]);
			
			return $this;
		}
		
		public function setTypeAs($type)
		{
			$this->update(['invoice_type' => $type]);
		}
		
		public function getPdfInvoicePath()
		{
		
		}
		
		public function setPdfInvoicePath($path)
		{
			$this->update([
				'pdf_invoice' => $path
			]);
		}
		
		public function setAsReturnedInvoice()
		{
			return $this->update([
				'is_updated' => true
			]);
		}
		
		public function getTotalTaxAttribute()
		{
			return $this->tax + $this->expenses()->sum('tax');
		}
		
		public function makeInvoiceUpdatedOrDeleted()
		{
			$data['is_updated'] = true;
			$data['is_deleted'] = true;
			
			foreach ($this->items as $item){
				if ($item['qty'] != $item['r_qty']){
					$data['is_deleted'] = false;
				}
			}
			$this->update($data);
		}
		
		public function getVat($type)
		{
			if ($type == 'purchase'){
				return 5;
			}
		}
		
		public function getDescriptionAttribute()
		{
			$description = '';
			if (app()->isLocale('ar')){
				if ($this->invoice_type == 'purchase')
					$description = ' مشتريات';
				
				if ($this->invoice_type == 'sale')
					$description = 'مبيعات';
				
				if ($this->invoice_type == 'beginning_inventory')
					$description = 'اول مدة';
				
				if ($this->invoice_type == 'r_sale')
					$description = 'مرتجع مبيعات';
				
				if ($this->invoice_type == 'r_purchase')
					$description = 'مرتجع مشتريات';
				
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
		
		public function getTitleAttribute()
		{
			
			if (in_array($this->invoice_type,['sale','r_sale']))
				return  !empty($this->sale) ? $this->sale->prefix.$this->id : "";
			else
				return  !empty($this->purchase) ? $this->purchase->prefix.$this->id : "";
			
		}
		
		public function getCreatedDateAttribute()
		{
			return $this->created_at->diffForHumans();
		}
		
		
		
		public function getSteakholderNameAttribute()
		{
			if (in_array($this->invoice_type,['sale','r_sale'])){
				return $this->sale->client->name;
			}
			
			return $this->purchase->vendor->name;
		}
		
		public function getSteakholderTypeAttribute()
		{
			if (in_array($this->invoice_type,['sale','r_sale'])){
				return __('pages/invoice.client');
			}
			
			return __('pages/invoice.vendor');
		}
		
		//served_title
		public function getServedTitleAttribute()
		{
			if (in_array($this->invoice_type,['sale','r_sale'])){
				return __('pages/invoice.salesman');
			}
			
			return __('pages/invoice.receiver');
		}
		
		public function getSteakholderPhoneNumberAttribute()
		{
			if (in_array($this->invoice_type,['sale','r_sale'])){
				return $this->sale->client->phone_number;
			}
			
			return $this->purchase->vendor->phone_number;
		}
		
		public function getServedByAttribute()
		{
			if (in_array($this->invoice_type,['sale','r_sale'])){
				return $this->sale->salesman->name;
			}
			
			return $this->purchase->receiver->name;
		}
		
		public function getBackgroundAssetAttribute()
		{
			
			
			if ($this->current_status == 'paid'){
				if (app()->isLocale('ar'))
					return asset('template/images/paid-ar.png');
				else
					return asset('template/images/paid.png');
				
			}else{
				
				if (app()->isLocale('ar'))
					return asset('template/images/paid.png');
				else
					return asset('template/images/paid.png');
				
			}
			
		}
		
		public function getPriceAttribute($value)
		{
			
			return money_format('%i',$value);
		}
		
		public function getTotalAttribute($value)
		{
			return money_format('%i',$value);
		}
		
		public function getTaxAttribute($value)
		{
//			return $value;
			return money_format('%.2n',$value);
		}
		
		public function getDiscountValueAttribute($value)
		{
			return money_format('%i',$value);
		}
		
		public function getNetAttribute($value)
		{
			
			return money_format('%i',$value);
		}
		
		public function getSubtotalAttribute($value)
		{
			
			return money_format('%i',$value);
		}
		
	}
