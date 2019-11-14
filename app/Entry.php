<?php
	
	namespace App;
	
	use App\Attributes\EntryAttributes;
	use App\DatabaseHelpers\EntryHelper;
	use App\Relationships\EntryRelationships;
	use Illuminate\Database\Eloquent\Model;
	
	class Entry extends Model
	{
		
		use EntryHelper,EntryAttributes,EntryRelationships;
		protected $guarded = [];
		//
	}
