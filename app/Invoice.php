<?php
	
	namespace App;
	
	use App\Attributes\InvoiceAttributes;
	use App\Core\CoreInvoice;
	use App\DatabaseHelpers\Invoice\FreshHelper;
	use App\DatabaseHelpers\InvoiceInterfaceHelper;
	use App\Relationships\InvoiceRelationship;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	use Illuminate\Database\Eloquent\Builder;
	class Invoice extends Model
	{


		use InvoiceRelationship,InvoiceAttributes,InvoiceInterfaceHelper,FreshHelper,SoftDeletes;
		use CoreInvoice;

		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope('currentManagerOrganizationOnly',function (Builder $builder){
					$builder->where('organization_id',auth()->user()->organization_id);
				});
			}
			if (auth()->check() && !auth()->user()->can('manage branches')){
				static::addGlobalScope('currentManagerInvoicesOnly',function (Builder $builder){
					$builder->where('creator_id',auth()->user()->id);
				});
			}
		}

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
