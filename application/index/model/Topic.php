<?php
namespace app\index\model;

use think\Model;


class Topic extends Model
{
    public static function getTopic($id){
        return self::withCount(['praises'])->find(['id'=>$id]);
    }

    public function user(){
        return $this->belongsTo('User','user_id'); 
    }

    public function praises(){
        return $this->hasMany('Praise','topic_id');
    }

    
    public static function getTopics(){
        return self::withCount(['praises'])->select();
    }   
}
     