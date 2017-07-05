<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Advertisement extends Controller
{
      public function index(){
          $list=db('advertisement')->select();

          if(empty($_SESSION['adminname'])){
              return view('login/login');
          }
          return view('advertisement/advert-list',['title'=>'广告列表','list'=>$list]);
      }
      public function insertadvert(){

          return view('advertisement/advertisement',['title'=>'广告列表']);
      }
      //上传图片
      public function upload(){


          $file = request()->file('image');


          $ll=request()->param();

          $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');


          $list['url']='\uploads\\';
          $list['url'].=$info->getSaveName();

          $list['name']=$ll['name'];
          $list['number']=$ll['number'];

          $result = db('advertisement')->insert($list);
//          return $this->display('Advertisement/advert-list');
//          return $this->index();
          $this->success();

      }
//      删除图片
      public function delete($id){
//          dump($url);die;
          $list=db('advertisement')->find($id);
          $url=".";
          $url.=strtr($list['url'],"\\","/");
      

          unlink($url);

          $result=db('advertisement')->delete($id);

          $this->success();
      }





}