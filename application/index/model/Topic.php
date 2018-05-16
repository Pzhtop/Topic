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

    public static function getPageInfo($page,$limitNum){
        $page=intval($page)<1 ? 1:intval($page);
        $count=self::count();
        $pageNum=ceil($count/$limitNum);
        $page=$page>$pageNum?$pageNum:$page;

        $showPages=[];
        for($leftPage=$page-3;$leftPage<=$page;$leftPage++){
            if($leftPage>0){
                $showPages[]=$leftPage;
            }
        }

        for($i=1;$i<=3;$i++){
            if ($page+$i<=$pageNum) {
                $showPages[]=$page+$i;
            }
        }
        return['page'=>$page,'showPages'=>$showPages,'pageNum'=>$pageNum];
    }

    public static function search($keyword){
        $cond=[];
        $cond['title']=['like','%'.$keyword.'%'];
        return self::withCount(['praises'])->where($cond)->select();
    }

    public static function getTagTopics($topicIds){
        $cond=[];
        $cond['id']=['in',$topicIds];
        return self::withCount(['praises'])->where($cond)->select();

    }

}
     