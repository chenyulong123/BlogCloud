<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Image extends Controller
{
    public function UidImagesList()
    {
        $list1=db('user')->select();
//        dump($list1[0]['id']);

        $list2='';
        foreach($list1 as $k=>$v) {
        $list2=db('albumcategory')->where('user_id',$v['id'])->select();
            $list1[$k]['imgcount']=count($list2);
        }
//        dump($list1);
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return view('image/UID-images-list',['title'=>'用户相册列表','list'=>$list1]);
    }
    public function imagesList($id,$name,$imgcount)
    {


        $list1=db('albumcategory')->where('user_id',$id)->select();
//        dump($list1);

        return view('image/images-list',['title'=>'相册列表页','id'=>$id,'name'=>$name,'imgcount'=>$imgcount,'list1'=>$list1]);
    }
    public function imagesDetails($id)
    {
        $list1=db('album')->where('gory_id',$id)->select();
//        dump($list1);
        return view('image/images-details',['title'=>'相册','list1'=>$list1,'id'=>$id]);
    }
    public function imagestatus($id){


        $list=db('album')->find($id);


        if($list['status']==0){
            $list['status']=1;
        }else{
            $list['status']=0;
        }


        $result=db('album')->where('id',$id)->update(['status'=>$list['status']]);
        $list2=db('album')->find($id);
        if ($result > 0) {
            $info['status'] = true;
            $info['id'] = $id;
            $info['name'] =$list2['status'];
            $info['info'] = 'ID为: ' . $id . ' 的用户已更新';
//            dump($info);die;
        } else {
            $info['status'] = false;
            $info['id'] = $id;
            $info['info'] = 'ID为: ' . $id . ' 的用户更新成功!';
        }


        return json($info);


    }
}