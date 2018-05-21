<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\User;
use app\admin\model\Praise;
use app\admin\model\Topic;
use app\admin\model\Reply;



class Index extends Controller
{
    function __construct(){

        parent::__construct();

        if($this->request->action()!=='login'){
            $user= session('adminUser');
            if(!$user||!$user->is_admin){
                return $this->error('您未登录或者不是管理员');
                
            }            
        }

    }

    public function login(){

        if(request()->isPost()){
            $login=input('post.username');
            $password=input('post.password');
            
            $cond=array();
            $cond['name|email']=$login;
            $cond['password']= md5(md5($password));
            
            $user=User::get($cond);
            
            if($user&&$user->is_admin){
                session('adminUser',$user);
                return $this->success('登陆成功','index/index');
            }
            return $this->error('登录失败或者不是管理员！');
            
        }
        return $this->fetch('login');
    }

    public function index(){
        $this->assign([
            'userCount'=>User::count(),
            'praiseCount'=>Praise::count(),
            'replyCount'=>Reply::count(),
            'topicCount'=>Topic::count(),
            'user'=>session('adminUser'),
            'active'=>'index',
        ]);
        echo $this->fetch('index');
    }

    public function topics(){
        
        $this->assign([
            'topics'=>Topic::where(['is_delete'=>0])->select(),
            'active'=>'topic',
            'user'=>session('adminUser'),
        ]);
        echo $this->fetch('topic_manage');
    }

    public function delTopic(){
        $topicId=input('get.topicId');
        $topic=Topic::find($topicId);
        $topic->is_delete=1;
        $topic->save();
        $this->success('删除成功');

    }


}

