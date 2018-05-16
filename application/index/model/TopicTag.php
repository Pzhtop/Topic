<?php
namespace app\index\model;

use think\Model;
use think\model\relation\BelongsTo;


class TopicTag extends Model{
    public static function getTopicTagsByTopicId($topicId){
        return self::where(['topic_id'=>$topicId])->select();
    }

    public function tag(){
        return $this->belongsTo('Tag','tag_id');
    }
}