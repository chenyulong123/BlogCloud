<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Bclass extends Controller
{
    /**
     * @return \think\response\View
     */
    public function newblogclass()
    {

            $list['pid'] = 0;
            $list['path'] = 0;

//            dump($list);
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return view('bclass/new-blog-class',['title'=>'新建分类','list'=>$list]);
    }

    /**
     * @return \think\response\View
     */
    public function blogclass()
    {
        $list=db('category')->where('pid','0')->select();
//        dump($list);
//
//        dump(count($list));

        //循环遍历 查询将改类子类的数量
        foreach($list as $k=>$v){
//            dump($v);
            $count=db('category')->where('pid',$v['id'])->select();
//            dump($count);
//            dump(count($count));
            $list[$k]['son_count']=count($count);
        }
//        dump($dd);
//        dump($list);
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return view('bclass/blogclass',['title'=>'博客分类','list'=>$list]);
    }
    public function blogsonclass()
    {
        $this->assign('title', '博客子类');
        return $this->fetch('blog-sonclass');
    }
//新建类的操作
    public function newclass(){


        $plist=input('post.');
//        dump($plist);
        $clist=db('category')->insert($plist);
        return $this->blogclass();

    }
    public function newsonblogclass($id)
    {
        $list=db('category')->find($id);
        //category表 分类用,拼接字串 pid,path
          $pid=$list['id'];
          $path=$list['path'].','.$list['id'];
//          dump($pid);
//          dump($path);
       $list=[
          'father_name'=>$list['category_name'],
          'pid'=>$pid,
           'path'=>$path
       ];
//        dump($list);
       return view('bclass/new-son-class',['title'=>'创建子分类','list'=>$list]);
       
    }
    public function newsonclass(){
        $list=input('post.');
//        dump($list);
        $list=db('category')->insert($list);
        return $this->blogclass();
    }
    public function sonblogclasslist($id){

         $list=db('category')->where('pid',$id)->select();
        foreach($list as $k=>$v){
//            dump($v);
            $count=db('category')->where('pid',$v['id'])->select();
//            dump($count);
//            dump(count($count));
            $list[$k]['son_count']=count($count);
        }


        return view('bclass/blog-sonclass',['title'=>'子分类列表','list'=>$list]);
    }
    public function editclassname($id){

        $list=db('category')->find($id);

        return view('bclass/edit-classname',['title'=>'更新分类名','list'=>$list]);

    }
    public function updateclassname($id){
//        dump($id);
        $list=input('post.');
       
            $result=db('category')->where('id',$id)->update(['category_name'=>$list['category_name'],'display'=>$list['display']]);
        return $this->blogclass();
    }
    public function deleteclass($id,$son_count){
//        dump($id);
//        dump($son_count);

        if($son_count!='0'){
//            dump('请先删除子类');
            return $this->blogclass();
        }else{
//            dump('删除成功');
            $result=db('category')->delete($id);
            return $this->blogclass();
;        }


    }
}