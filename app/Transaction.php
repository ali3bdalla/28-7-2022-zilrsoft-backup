<?php
	
	namespace App;
	
	use App\DatabaseHelpers\TransactionHelper;
	use Illuminate\Database\Eloquent\Model;
	
	class Transaction extends Model
	{
		use TransactionHelper;
		
		protected $guarded = [];
		
		
		public function creditable()
		{
			return $this->morphTo();
		}
		
		public function debitable()
		{
			return $this->morphTo();
		}
		
		
	}
