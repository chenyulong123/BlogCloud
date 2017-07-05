<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Statistics extends Controller
{
       public function index(){
           $bclass=db('category')->count();
           $blog=db('article')->count();
           $friend=db('friend')->count();
           $user=db('userinfo')->count();
           $album=db('album')->count();
           $albumc=db('albumcategory')->count();
           $advertisement=db('advertisement')->count();
           $list=[
               'bclass'=>$bclass,
               'blog'=>$blog,
               'friend'=>$friend,
               'user'=>$user,
               'album'=>$album,
               'albumc'=>$albumc,
               'advertisement'=>$advertisement
           ];
           if(empty($_SESSION['adminname'])){
               return view('login/login');
           }

           return view('index/stats',['title'=>'统计','list'=>$list]);
       }
}