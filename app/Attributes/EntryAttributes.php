<?php
	
	
	namespace App\Attributes;
	
	
	trait EntryAttributes
	{
		public function to()
		{
			return $this->morphTo();
		}
		
		
		public function from()
		{
			return $this->morphTo();
		}
		
		
	}