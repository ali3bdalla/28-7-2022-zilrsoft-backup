<?php
namespace App\Attributes;

trait BranchAttributes {

    use BranchLocale;

}


trait  BranchLocale
{
    public function  getNameAttribute($value){
        if(app()->isLocale('ar')){
            return $this->ar_name;
        }


        return $value;
    }

}
