<?php

namespace app\index\controller;

use think\Controller;
use app\index\model\Reply as ReplyModel;


class Reply extends Controller{
   
    public function newReply(){
        
        $postData=input('post.');
        $reply=new ReplyModel();
        $user=session('user');
        $reply->content=$postData['content'];
        if(isset($postData['repli_id'])&&intval($postData['reply_id'])>0){
            $reply->reply_id=intval($postData['reply_id']);
        }

        $reply->topic_id = $postData['topic_id'];
        $reply->created_at=intval(microtime(true));
        $reply->user_id=$user->id;
        $reply->save();
        $this->success('恭喜！回复成功！');
    }

    

}