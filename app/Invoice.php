<?php
	
	namespace App;
	
	use App\Attributes\InvoiceAttributes;
	use App\Core\CoreInvoice;
	use App\DatabaseHelpers\Invoice\FreshHelper;
	use App\DatabaseHelpers\InvoiceInterfaceHelper;
	use App\OrmScope\InvoiceScope;
	use App\Relationships\InvoiceRelationship;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Invoice extends Model
	{
		
		use InvoiceRelationship,InvoiceAttributes,InvoiceInterfaceHelper,FreshHelper,SoftDeletes;
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
