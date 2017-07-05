<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
session_start();
class Advertisement extends Controller
{
      public function Advertisement(){
          $list=db('Advertisement')->select();


          return view('activity/advertisement',['title'=>'活动','list'=>$list]);

      }
}