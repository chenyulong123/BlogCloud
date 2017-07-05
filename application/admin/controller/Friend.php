<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Friend extends Controller
{
       public function index(){
//           $list=Db::table('friend')->alias('f1')->join('user u1','f1.user_id=u1.id')->select();
           $list=db('friend')->select();


           foreach($list as $k=>$v){

               $list[$k]['user_name']=implode(db('user')->field('name')->find($v['user_id']));

               $list[$k]['friend_name']=implode(db('user')->field('name')->find($v['friend_id']));
           }
//           dump($list);
           if(empty($_SESSION['adminname'])){
               return view('login/login');
           }
           return view('friend/friend',['title'=>'好友','list'=>$list]);
       }
}