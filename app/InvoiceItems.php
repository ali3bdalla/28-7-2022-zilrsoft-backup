<?php
	
	namespace App;
	
	use App\Attributes\InvoiceItemAttributes;
	use App\DatabaseHelpers\InvoiceItemHelper;
	use App\Relationships\InvoiceItemRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	
	class InvoiceItems extends Model
	{
		use InvoiceItemRelationships,InvoiceItemAttributes,InvoiceItemHelper;
		
		//
		protected $guarded = [
		
		];
		protected $casts = [
			'tax' => 'float',
			'total' => 'float',
		];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
	}
