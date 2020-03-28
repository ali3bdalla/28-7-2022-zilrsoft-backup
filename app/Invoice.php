<?php
	
	namespace App;
	
	use App\Attributes\InvoiceAttributes;
	use App\Core\CoreInvoice;
	use App\DatabaseHelpers\Invoice\FreshHelper;
	use App\DatabaseHelpers\InvoiceInterfaceHelper;
	use App\OrmScope\InvoiceScope;
	use App\Relationships\InvoiceRelationship;
	use Illuminate\Database\Eloquent\Model;
	
	class Invoice extends Model
	{
		
		use InvoiceRelationship,InvoiceAttributes,InvoiceInterfaceHelper,FreshHelper;
		use InvoiceScope,CoreInvoice;
		protected $appends = [
			'description',
			'title',
			'user_id'
		];
		protected $guarded = [];
		
		protected $casts = [
			'printable_price' => 'boolean'
		];
		
	}
