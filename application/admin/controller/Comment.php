<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Comment extends Controller
{
    public function commentList()
    {
        $list=db('responce')->select();
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return view('comment/comment-list',['title'=>'评论列表','list'=>$list]);
    }

    /** 显示隐藏评论的操作
     * @param $id 评论id
     * @return \think\response\View
     */
    public function comdisplay($id){
        $list=db('responce')->find($id);

//        dump($list);die;
        if($list['display']==0){
            $list['display']=1;
        }else{
            $list['display']=0;
        }
        $result=db('responce')->where('id',$id)->update(['display'=>$list['display']]);

        $list2=db('responce')->find($id);
//        dump($list2);die;
        if ($result > 0) {
            $info['status'] = true;
            $info['id'] = $id;
            $info['name'] =$list2['display'];
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