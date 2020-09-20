<?php


	namespace App\Relationships;


	use App\Attachment;
    use App\Models\Category;
	use App\Models\InvoiceItems;
	use App\Models\ItemExpenses;
	use App\Models\ItemFilters;
	use App\Models\ItemSerials;
	use App\Models\ItemStatistic;
	use App\Models\Manager;
	use App\Models\Organization;
	use App\Models\Transaction;
	use App\Models\WarrantySubscription;

	trait ItemRelationships
	{

        // public function attachments()
        // {
        //     return $this->morphMany(Attachment::class,'attachable');
	    // }



		// public function warranty()
		// {
		// 	return $this->belongsTo(WarrantySubscription::class,'warranty_subscription_id');
		// }

		// public function statistics()
		// {
		// 	return $this->hasOne(ItemStatistic::class,'item_id');
		// }

		// public function expenses()
		// {
		// 	return $this->hasMany(ItemExpenses::class,'item_id');
		// }

		public function organization()
		{
			return $this->belongsTo(Organization::class,'organizaiton_id');
		}

		public function serials()
		{
			return $this->hasMany(ItemSerials::class,'item_id');
		}

		public function pipline()
		{
			return $this->hasMany(InvoiceItems::class,'item_id');
		}

		public function category()
		{
			return $this->belongsTo(Category::class,'category_id');
		}

		public function filters()
		{
			return $this->hasMany(ItemFilters::class,'item_id');
		}

		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}

		// public function credit_transaction()
		// {
		// 	return $this->morphMany(Transaction::class,'creditable');
		// }

		// public function debit_transaction()
		// {
		// 	return $this->morphMany(Transaction::class,'debitable');
		// }

	}
