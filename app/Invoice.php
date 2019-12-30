<?php
	
	namespace App;
	
	use App\Attributes\InvoiceAttributes;
	use App\DatabaseHelpers\Invoice\FreshHelper;
	use App\DatabaseHelpers\InvoiceInterfaceHelper;
	use App\OrmScope\InvoiceScope;
	use App\Relationships\InvoiceRelationship;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	
	class Invoice extends Model
	{
		
		use InvoiceRelationship,InvoiceAttributes,InvoiceInterfaceHelper,FreshHelper;
		use InvoiceScope;
		protected $appends = [
			'description',
			'title',
			'user_id'
		];
		protected $guarded = [];
		
		
		
	}
