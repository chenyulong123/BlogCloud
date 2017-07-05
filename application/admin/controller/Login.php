<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

session_start();
class Login extends Controller
{
    public function index()
    {
        return view('login',['title'=>'登陆']);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function login()
    {
        $list=Db::query('select * from admin');



        $data=input('post.');



        $admin=Db::query('select `name` from `admin` where `name`= "'.$data['name'].'"' );
        if (empty($admin)){
            return('改用户不存在');
        }
        $admin=Db::query('select `password` from `admin` where `password`= "'.$data['password'].'"' );
        if (empty($admin)){
            return('密码错误');
        }
        if($list[0]['name']==$data['name']&&$list[0]['password']==$data['password']){
            $_SESSION['adminname']=$data['name'];
            $this->assign('title', '后首页');
            return $this->fetch('index/index');

        }else{

            return('输入错误');
        }
    }

    public function out()
    {
        $_SESSION['adminname']='';
        $this->assign('title', '登录');
        return $this->fetch('login/login');
    }

}
