<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;
session_start();
class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return 用户中心模板
     */
    public function index()
    {
        return view('user/mange-msg', ['title' => '消息', 'username' => $_SESSION['name']]);
    }
    
    /**
     *  @return 用户信息模板
     */
    public function Person_Info()
    {
        return view('user/mange-personinfo', ['title' => '个人信息']);
    }
    
    /**
     * @return 用户信息设置
     */
    public function Person_Mod()
    {
        return view('user/mange-modeinfo', ['title' => '个人信息设置', 'username' => $_SESSION['name']]);
    }
    
    /**
     * @return 用户通行证管理模板
     */
    public function Person_Passport()
    {
        return view('user/mange-passport', ['title' => '用户通行证管理']);
    }
    
    /**
     * @return 用户隐私模板
     */
    public function Person_Secret()
    {
        return view('user/mange-secret', ['title' => '用户隐私设置']);
    }
    
    /**
     * @return 上传图片后的新路径
     */
    public function Upload_img()
    {

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('icon');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $path = $info->getSaveName();
            $road = '\uploads\\'.$path;
            $row = Db::table('userinfo')->where('name', $_SESSION['name'])->update(['icon' => $road]);
            if ($row) {
                $_SESSION['icon'] = $road;
                $this->success('修改头像成功');
            } else {
                $this->error('修改头像失败');
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }
    
    /**
     *  将用户信息写入数据库
     */
    public function Set_UserInfo()
    {
        $request = Request::instance()->post();
        
        $request = Request::instance()->post();
        
        $result = Db::table('userinfo')->where('name', $request['name'])->find();
        if ($result) {
            $validate = new Validate([
                'intro' => 'length:4,50'
            ]);
            if ($validate->check($request)) {
                $data = ['name' => $request['name'], 'sex' => $request['sex'], 'birthday_year' => $request['birthday_year'], 'birthday_month' => $request['birthday_month'], 'birthday_day' => $request['birthday_day'], 'constellation' => $request['constellation'], 'blood' => $request['blood'], 'live_province' => $request['live_province'], 'live_city' => $request['live_city'], 'intro' => $request['intro']];
                $final = Db::table('userinfo')->where('name', $data['name'])->update($data);
                if ($final) {
                    $this->success('编辑成功', 'user/mange-modeinfo');
                } else {
                    $this->error('编辑失败');
                }
        
            } else {
                dump($validate->getError());
            }
            
        } else {
            
            $validate = new Validate([
                'intro' => 'length:4,50'
            ]);
            if ($validate->check($request)) {
                $data = ['name' => $request['name'], 'sex' => $request['sex'], 'birthday_year' => $request['birthday_year'], 'birthday_month' => $request['birthday_month'], 'birthday_day' => $request['birthday_day'], 'constellation' => $request['constellation'], 'blood' => $request['blood'], 'live_province' => $request['live_province'], 'live_city' => $request['live_city'], 'intro' => $request['intro']];
                $final = Db::table('userinfo')->insert($data);
                if ($final) {
                    $this->success('编辑成功', 'user/mange-modeinfo');
                } else {
                    $this->error('编辑失败');
                }
        
            } else {
                dump($validate->getError());
            }
        }
        
       
    }
    
   
    
    
    
    
}
