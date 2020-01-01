<?php
	
	namespace App;
	
	use App\Attributes\SaleInvoiceAttributes;
	use App\DatabaseHelpers\SaleInvoiceHelper;
	use App\Relationships\SaleInvoiceRelationships;
	use App\Scopes\OrganizationScope;
	use App\Scopes\QuotationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class SaleInvoice extends Model
	{
		//
		use SaleInvoiceRelationships,SaleInvoiceAttributes,SaleInvoiceHelper,\App\Core\CoreInvoice;
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
				
				static::addGlobalScope(new QuotationScope());
			}
			
			
		}
		
	}
