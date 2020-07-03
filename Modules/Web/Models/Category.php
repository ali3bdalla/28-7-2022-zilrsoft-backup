<?php


namespace Modules\Web\Models;


trait Category
{
    public function getWebCoverUrlAttribute()
    {
        return  "https://picsum.photos/500/500";
        return $this->cover;
//        $attachments = $this->attachments()->where('type','image');
//        return $attachments->count() > 0 ? $attachments->first()->url : '/Web/template/img/products/man-3.jpg';
    }


    public function getWebNameAttribute()
    {
        return $this->locale_name;
    }

    public function webImage($imgNum)
    {

//        echo  array_rand(['https://www.w3schools.com/jsref/klematis.jpg', 'https://www.w3schools.com/jsref/klematis2.jpg', 'https://www.w3schools.com/jsref/smiley.gif']);
//        exit();
//
        $arr = ['https://www.w3schools.com/jsref/klematis.jpg', 'https://www.w3schools.com/jsref/klematis2.jpg', 'https://www.w3schools.com/jsref/smiley.gif'];
        $rand = array_rand($arr,1);
        return $arr[$rand];
    }

}