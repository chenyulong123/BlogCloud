<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

session_start();
class User extends Controller
{
    public function index()
    {
        $this->assign('title', '新增用户');
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return $this->fetch('new-user');
    }
    public function insert()
    {
        $data=input('post.');
//        dump($data);
//

        //助手函数
        $result = db('userinfo')->insert($data);
        return $this->user();


    }
    public function user()
    {
        $list = Db::name('userinfo')->select();
//        dump($list);
//        $this->assign('title', '用户列表');
//        $this->assign('list', $list);
//        return $this->fetch('users',);
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return view('users',['title'=>'用户列表','list'=>$list]);
    }
    public function delete($id){
        $result = Db::name('userinfo')->delete($id);
//        dump($reslut);
//        dump('111');
        return $this->user();
    }
    public function edit($id){

        $list = Db::name('userinfo')->find($id);
//        dump($list);
        return view('edit-user',['title'=>'编辑用户','list'=>$list]);
    }

    /**
     * @param $id 更新数据的ID
     * 更新用户信息
     */
    public function update($id){
         $info = input('put.');
//        $info = $request->put();
//         var_dump($info);
//
        $data = [

            'sex' => $info['sex'],
            'birthday_year' => $info['birthday_year'],
            'birthday_month' => $info['birthday_month'],
            'birthday_day' => $info['birthday_day'],
            'constellation' => $info['constellation'],
            'blood' => $info['blood'],
            'live_province' => $info['live_province'],
            'live_city' => $info['live_city']
        ];
//        dump($data);
        //执行更新
        $result = Db::name('userinfo')->where('id',$id)->update($data);
        //判断执行情况
        if ($result > 0) {
            return $this->success('编辑成功', url('admin/user/user'));
        } else {
            return $this->success('编辑失败(ノ°ο°)ノ高能预警!');
        }
    }
    public function detail($id){
//          dump('111');
//        dump('$id');
        $list = Db::name('userinfo')->find($id);
//        dump($list);
        return view('user-details',['title'=>'用户详情','list'=>$list]);
    }
    
}
