<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
//Blog 管理 -置顶 -首页 -允许评论
class blog extends Controller
{
    public function index()
    {
        $list=db('article')->select();


        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        
        return view('blog/blog',['title'=>'博客列表','list'=>$list]);


    }

    //博客列表的一些操作 head
    //博客是否显示
    public function page($page)
    {
        $list=db('article')->page($page,6)->select();
        return view('blog/blog',['title'=>'博客列表','list'=>$list]);
    }
    public function bdisplay($id){

        $list=db('article')->find($id);

        if($list['display']==0){
            $list['display']=1;
        }else{
            $list['display']=0;
        }
        $result=db('article')->where('id',$id)->update(['display'=>$list['display']]);
        return $this->index();
    }
    //博客置顶操作
    public function top($id){
        $list=db('article')->find($id);

        if($list['always_top']==0){
            $list['always_top']=1;
        }else{
            $list['always_top']=0;
        }
        $result=db('article')->where('id',$id)->update(['always_top'=>$list['always_top']]);
        return $this->index();
    }
    //是否允许博客评论
    public function comment($id){
        $list=db('article')->find($id);

        if($list['allow_comment']==0){
            $list['allow_comment']=1;
        }else{
            $list['allow_comment']=0;
        }
        $result=db('article')->where('id',$id)->update(['allow_comment'=>$list['allow_comment']]);
        return $this->index();
    }
//    //是否推荐到首页
    public function recommend($id){
        $list=db('article')->find($id);

        if($list['allow_recommend']==0){
            $list['allow_recommend']=1;
        }else{
            $list['allow_recommend']=0;
        }
        $result=db('article')->where('id',$id)->update(['allow_recommend'=>$list['allow_recommend']]);
        return $this->index();
    }
    //博客列表的一些操作 end
    public function blog_details($id)
    {

        $list=db('article')->find($id);

       return view('blog/blog-details',['title'=>'博客详情页','list'=>$list]);
    }
    public function delete($id){

        $row=db('article')->delete($id);

        if($row > 0){
            $info['status'] = true;
            $info['id'] = $id;
            $info['info']= 'Id为: ' .$id.' 用户已经删除';
        }else{
            $info['status'] = false;
            $info['id'] = $id;
            $info['info']= 'Id为: ' .$id.' 用户删除失败,请重试';
        }

        return json($info);
    }
}