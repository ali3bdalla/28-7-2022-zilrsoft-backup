<?php

	namespace App\Models;


    /**
     * @property mixed id
     */
    class ShippingAddress extends BaseModel
	{
		protected $guarded = [];

		public function user()
		{
			return $this->belongsTo(User::class, 'user_id');
		}

		public function city()
		{
			return $this->belongsTo(City::class, 'city_id')->with('country');
		}

	}
