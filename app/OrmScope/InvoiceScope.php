<?php
	
	
	namespace App\OrmScope;
	
	use Illuminate\Database\Eloquent\Builder;
	
	trait InvoiceScope
	{
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope('currentManagerOrganizationOnly',function (Builder $builder){
					$builder->where('organization_id',auth()->user()->organization_id);
				});
			}
//			if (auth()->check() && !auth()->user()->can('manage branches')){
//				static::addGlobalScope('currentManagerInvoicesOnly',function (Builder $builder){
//					$builder->where('creator_id',auth()->user()->id);
//				});
//			}
		}
	}