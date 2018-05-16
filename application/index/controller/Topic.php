<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Tag;
use app\index\model\Topic as TopciModel;
use app\index\model\TopicTag;
use app\index\model\Praise;


class Topic extends Controller{
    
    

    public function newTopic(){

        if(request()->isPost()){
            $user=session('user');
            $postData=input('post.');
            $topic=new TopciModel();
            $topic->title=$postData['title'];
            $topic->category_id=$postData['category_id'];
            $topic->content=$postData['content'];
            $topic->user_id=$user->id;
            $topic->created_at=intval(microtime(true));
            $topic->save();

            //处理标签
            $tags=$postData['tags'];
            foreach ($tags as $tag) {
                if(is_numeric($tag)){
                    $this->createTopicTag($tag,$topic->id);
                    continue;
                }
                 $newTag=$this->createTag($tag);
                 $this->createTopicTag($newTag->id,$topic->id);
                 


            }
            $this->success('恭喜！帖子创建成功！');
        }

        $this->assign([
            'user'=>session('user'),
            'category'=>config('category'),
            'tags'=>Tag::all(),
        ]);
        
        echo $this->fetch('new_topic', ['user' => session('user')]);
    }

    private function createTopicTag($tagId,$topicId){
        $topicTag=new TopicTag();
        $topicTag->tag_id=$tagId;
        $topicTag->topic_id=$topicId;
        $topicTag->save();

    }

    private function createTag($name){
        $tag=new Tag();
        $tag->name=$name;
        $tag->save();
        return $tag;
    }

    public function detail(){
        $topicId=input('get.id');
        $topic=TopciModel::getTopic($topicId);
      
        $user=session('user');
        $this->assign([
        'user'=>$user,
            'topic'=>$topic,
            
             'topicTags' => TopicTag::getTopicTagsByTopicId($topic->id),
            
             'categoryNames' => getCategoryNames($topic->category_id),

        ]);
        echo $this->fetch('detail');
    }

    public function praise(){
        $user=session('user');
        if(!$user){
            return false;
        }
        $topicId=intval(input('get.topicId'));
        $praise=Praise::get(['user_id'=>$user->id,'topic_id'=>$topicId]);

        if($praise){
            $praise->delete();
        }else {
        $praise=new Praise();
        $praise->user_id=$user->id;
        $praise->topic_id=$topicId;
        $praise->created_at=intval(microtime(true));
        $praise->save();
        }
    }

    public function index(){
        $topics=TopciModel::getTopics();
        $this->assign([
            'user'=>session('user'),
            'topics'=>$topics,
            
        ]);
        
        echo $this->fetch('index');
    }


}
 