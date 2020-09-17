<?php
namespace App\Relationships;
use App\Models\Filter;
use App\Models\Manager;

trait FilterValuesRelationships {
	


	public function filter()
	{
		return $this->belongsTo(Filter::class,'filter_id');
	}


	public function manager()
	{
		return $this->belongsTo(Manager::class,'creator_id');
	}
}