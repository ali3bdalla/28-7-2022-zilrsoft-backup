<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class OrganizationGateway extends Model
	{
		protected $table = 'organization_gateway';
		//
//	use SoftDeletes;
		
		protected $guarded = [];

//	protected static function boot ()
//	{
//		parent::boot ();
//		if (auth ()->check ()){
//			static::addGlobalScope (new OrganizationScope(auth ()->user ()->organization_id));
//		}
//	}
////

//	public function organization()
//	{
//		return $this->belongsTo(Organization::class,'organization_id');
//	}
//
//
//
//	public function gateway()
//	{
//		return $this->belongsTo(Gateway::class,'gateway_id');
//	}
//

//	public function setAsDefaultMethod ()
//	{
//		$this->update ([
//			'is_default' => true
//		]);
//
//		$this->where ('id','!=',$this->id)->update ([
//			'is_default' => false
//		]);
//
//	}

//	public function getNameAttribute ($value)
//	{
//		return $this->ar_name;
//
//	}
//
//	public function children ()
//	{
//		return $this->hasMany (Gateway::class,'parent_id');
//	}
//
//	public function parent ()
//	{
//		return $this->belongsTo (Gateway::class,'parent_id');
//	}

//	public function accounts ()
//	{
//		return $this->hasMany (GatewayAccounts::class,'pay_way_id');
//	}
	
	
	}
