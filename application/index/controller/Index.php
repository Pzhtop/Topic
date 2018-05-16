<?php
namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Db;


class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';

    }

    public function register(){
        if(request()->isPost()){
            $postData=input('post.');
             
            if(!captcha_check($postData['verifycode'])){
                return $this->error('验证码校验失败!');
            }

            if(!$this->checkPassword($postData)){
                return $this->error('密码校验失败!');
            }
            $user=new User();
            $user->name=$postData['username'];
            $user->email=$postData['email'];
            $user->avatar='images/avatar.jpg';
            $user->password=md5(md5($postData['password']));
            $user->created_at=intval(microtime(true));
            $user->save();
            return $this->success('恭喜！注册成功！');

        }
        
        echo $this->fetch();
    } 

    public function login(){
       if(request()->isPost()){
           $login=input('post.login');
           $password=input('post.password');
           $cond=[];
           $cond['name']=$login;
           $cond['password']=md5(md5($password));
           
           $user=User::get($cond);
        
           if($user){
               session('user',$user);
               return $this->success('恭喜！登陆成功！');
                
           }else {
               return $this->success('抱歉，登录失败！');
           }
           
        }
        // var_dump(session('user'));
        echo $this->fetch('login',['user'=>session('user')]);
    }

    public function logout(){
        session('user', null);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $this->success('退出成功', 'index/index/login');
        
    }

    private function checkPassword($data){
        if(!$data['password']){
            return false;
        }
        if($data['password']!==$data['password_confirmation']){
            return false;
        }
        return true;
    }
}
