<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Vip extends Controller
{

    /*
    *
    */
    public function usersBlack()
    {
        $this->assign('title', '用户黑面单');
        return $this->fetch('users-black');
    }
    public function usersFriend()
    {
        $this->assign('title', '用户好友');
        return $this->fetch('users-friend');
    }
    public function usersfriendslist()
    {
        $this->assign('title', '用户还有列表');
        return $this->fetch('users-friends-list');
    }

}