<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
session_start();
class Friend extends Controller
{
    /**
     * 显示资源列表
     *
     * @return 好友列表模板
     */
    public function index()
    {
        $result = Db::table('friend')->where('user_id', $_SESSION['id'])->field('friend_id')->select();
        $val = array();
        for ($i = 0; $i < count($result); $i++) {
            $val = Db::table('user')->where('id', $result[$i]['friend_id'])->field('name')->find();
        }
        
        return view('friend/friend-list', ['title'=> '好友列表', 'fr_list' => $val]);
    }
    
    /**
     * @return 好友新鲜事模板
     */
    public function Friend_State()
    {
        return view('friend/friend-state');
    }
    
    /**
     * @return 好友邀请模板
     */
    public function Friend_Invite()
    {
        $request = Db::table('friend')
            ->alias('fd')
            ->join('user u', 'u.id = fd.user_id')
            ->join('blog_name bl', 'u.id = bl.user_id')
            ->where('fd.friend_id', $_SESSION['id'])
            ->field('fd.private_msg, u.name as username, bl.name')->select();
        return view('friend/friend-invite',['message' => $request]);
    }
    
    /**
     * @return 好友搜索模板
     */
    public function Friend_Search()
    {
        return view('friend/friend-search',['blog_name' => '']);
    }
    
    /**
     *  搜索好友
     */
    public function Search_Friend()
    {
        $request = Request::instance()->post();
        $domain = $request['domain'];
        
        if ($request) {
            $row = Db::table('blog_name')->where('name', 'like', "%$domain%")->limit(5)->select();
            return json($row);
        } else {
            return false;
        }
        
    }
    
    /**
     * 搜索可能认识的好友
     */
    public function Search_MayK_Friend()
    {
        $arr = array();
        for ($i = 0; $i < 5; $i++) {
            $arr[] = mt_rand(1, 5);
        }
        
        for ($i = 0; $i < 5; $i++) {
            $result[] = Db::table('blog_name')->where('id', $arr[$i])->find();
        }
        return json($result);
    }
    
    /**
     *  添加好友
     */
    public function Add_Friend($id)
    {
        //自己不能加自己好友
        
        if ($id != $_SESSION['id']) {
            //判断好友是否存在
            $final = Db::table('friend')->where(['user_id' => $_SESSION['id'], 'friend_id' => $id])->find();
            
            //是否允许添加好友
            $name = Db::table('user')->where('id', $id)->field('name')->find();
            $status = Db::table('userinfo')->where('name', $name['name'])->field('status')->find();
            
            //给对方发消息等待回应
            $message = $_SESSION['name'].' 添加您为好友';
            $msg = ['message' => $message];
            Db::table('userinfo')->where('name', $name['name'])->data($msg)->update();
            //写入数据库
            if (empty($final) && $status['status'] == 0) {
                $data = ['user_id' => $_SESSION['id'], 'friend_id' => $id];
                $result = Db::table('friend')->insert($data);
                if ($result) {
                    return json(true);
                } else {
                    return json(false);
                }
            } else {
                return json(false);
            }
            
        } else {
            return json(false);
        }
        
    }
    
    /**
     * 删除好友
     */
    public function Delete_Friend($name)
    {
        
        $friend_id = Db::table('user')->where('name', $name)->field('id')->find();
        $row = Db::table('friend')->where(['user_id' => $_SESSION['id'], 'friend_id' => $friend_id['id']])->delete();
        return json($row);
    }
    
    /**
     *  给好友发消息
     */
    public function Private_Message()
    {
        $request = Request::instance()->post();
        $row = Db::table('friend')->where('user_id', $_SESSION['id'])->update(['private_msg' => $request['msg']]);
        if ($row) {
            return json(true);
        } else {
            return json(false);
        }
    }
    
    
    
    
}