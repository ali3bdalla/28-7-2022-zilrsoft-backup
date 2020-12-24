<?php

namespace App\Models;



class HashMap extends BaseModel
{


    protected $table = 'hash_maps';

    protected $guarded = [];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'parents' => 'array',
        // 'children' => 'array'
    ];


    public function mappable(){
        return $this->morphTo();
    }
    public function getChildrenAttribute($value){
        $array = explode(',',stripcslashes($value));

        $result = [];
        foreach($array as $el)
        {
            $result[] = $el;
        }
        return $result;
    }


    public function getParentsAttribute($value){
        $array = explode(',',stripcslashes($value));
        $result = [];
        foreach($array as $el)
        {
            $result[] = $el;
        }
        return $result;
    }

}
