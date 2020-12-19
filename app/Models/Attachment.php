<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	/**
	 * @property mixed actual_path
	 * @property mixed attachable
	 * @property mixed is_master
	 * @property mixed id
	 */
	class Attachment extends BaseModel
	{
		
		use SoftDeletes;
		
		protected $guarded = [];
		
		
		protected $hidden = [ 'attachable_type', 'updated_at', 'created_at'];
		
		public function setIsMasterAttribute($value)
		{
			$this->attributes['is_master'] = $value;
		}
		
		public function removePrevMasterAttachment($attachable)
		{
			if($this->is_master) {
				$attachable->attachments()->where('id', '!=', $this->id)->update(
					[
						'is_master' => false
					]
				);
				
			}
			
		}

//
		
		public function attachable()
		{
			return $this->morphTo();
		}

	}
