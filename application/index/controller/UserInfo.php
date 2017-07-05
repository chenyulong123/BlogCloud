<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
session_start();
class UserInfo extends Controller
{
    /**
     * 显示用户的个人信息
     *
     * @return \think\Response
     */
    public function User_Info()
    {
        $result = Db::table('userinfo')->where('name', $_SESSION['name'])->find();
        return json($result);
    }
    
    /**
     * 修改用户密码
     */
    public function User_ModPass()
    {
        Request::instance()->get();
        $request = Request::instance()->post();
        $id = Db::table('user')->where('name', $_SESSION['name'])->find();
        if ($id['password'] === $request['pass']) {
            $row = Db::table('user')->where('name', $_SESSION['name'])->update(['password' => $request['repass']]);
            if ($row) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * 用户隐私设置
     */
    public function Mod_Secret()
    {
        $request = Request::instance()->post();
        $row = Db::table('userinfo')->where('name', $_SESSION['name'])->update(['status' => $request['refuse_friend']]);
        if ($row) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败');
        }
    }
    
    /**
     *  添加好友信息
     */
    public function Accept_Message()
    {
        $msg = Db::table('userinfo')->where('name', $_SESSION['name'])->field('message')->find();
        return json($msg);
    }
    
    /**
     *  同意添加好友
     */
    public function Add_Friend_Ag()
    {
        $data = ['message' => ""];
        $result = Db::table('userinfo')->where('name', $_SESSION['name'])->data($data)->update();
        if ($result) {
            $this->success('添加好友成功');
        } else {
            $this->error('添加好友失败');
        }
    }
    
    /**
     *  拒绝添加好友
     */
    public function Friend_Del($name)
    {
        $result = Db::table('user')->where('name', $name)->field('id')->find();
        $row = Db::table('friend')->where(['friend_id' => $_SESSION['id'], 'user_id' => $result['id']])->delete();
        $data = ['message' => ""];
        $msg = Db::table('userinfo')->where('name', $_SESSION['name'])->data($data)->update();
        return json($msg);
        
    }
    
    /**
     *  美女图片接口
     */
    public function API_interface()
    {
        header("type-content:text/html;charset=utf-8");
        //curl初始化
        $curl = curl_init();
        //curl配置
//        1.设置APIKEY
        $apikey = "4d04ecebf9718099d7e9cc80fe6aeb2e";
        $num = 10;
//        url设置
        $url = 'http://api.tianapi.com/meinv/?key='.$apikey.'&num='.$num;
        curl_setopt($curl, CURLOPT_URL, $url);
        
        // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
//        执行curl
        $data = curl_exec($curl);
//        关闭curl
        $jsonObj = json_decode($data);
        $news_list = $jsonObj->newslist;
        return view('person/interface', ['title' => '美女图片', 'cookbook' => $news_list]);
    }
    
}