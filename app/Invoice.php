<?php
	
	namespace App;
	
	use App\Attributes\InvoiceAttributes;
	use App\DatabaseHelpers\Invoice\FreshHelper;
	use App\DatabaseHelpers\InvoiceInterfaceHelper;
	use App\Relationships\InvoiceRelationship;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class Invoice extends Model
	{
		
		use InvoiceRelationship,InvoiceAttributes,InvoiceInterfaceHelper,FreshHelper;
		
		protected $appends = [
			'description',
			'title',
			'user_id'
		];
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
	}
