<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

session_start();

class Album extends Controller
{
    /**
     * 显示资源列表
     *
     * @return 文件管理模板
     */
    public function index()
    {
        $result = Db::table('albumcategory')->where('user_id',  $_SESSION['id'])->select();
        //        dump($result);
        return view('album/album-mange', ['title' => '文件管理', 'category' => $result]);
    }
    
    /**
     * @return 文件上传模板
     */
    public function Album_Upload()
    {
        $result = Db::table('albumcategory')->where('user_id', $_SESSION['id'])->field('name, id')->select();
        //        dump($result);
        return view('album/album-upload', ['title' => '文件上传', 'category' => $result]);
    }
    
    /**
     *  创建相册分类
     */
    public function Album_Category()
    {
        $request = Request::instance()->post();
        $row = Db::table('albumcategory')->insert(['name' => $request['name'], 'user_id' => $_SESSION['id']]);
        
        $new_id = Db::table('albumcategory')->getLastInsID();
        
        $result = Db::table('album')->insert(['user_id' => $_SESSION['id'], 'gory_id' => $new_id]);
        if ($result) {
            $this->success('添加图片集成功');
        } else {
            $this->error('添加图片集失败');
        }
    }
    
    /**
     * 删除图片集
     */
    public function Del_AlbumCategory($cid)
    {
        $id = stristr($cid, '.', true);
        
        $row = Db::table('albumcategory')->where('id', $id)->delete();
        
        $final = Db::table('album')->where('gory_id', $id)->delete();
        
        if ($row && $final) {
            $this->success('删除图片集成功');
        } else {
            $this->error('删除图片集成功');
        }
    }
    
    /**
     * 上传图片
     */
    public function Upload_Image()
    {
        //获取图片分类ID
        $cid = request()->post();
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        $path = $info->getSaveName();
        $file_path = '\uploads\\'.$path;
        //将图片路径写入数据库
        if($info){
            $row = Db::table('album')->insert(['name' => $file_path, 'gory_id' => $cid['FolderID'], 'user_id' => $_SESSION['id']]);
            if ($row) {
                $this->success('添加图片成功');
            } else {
                $this->error('图片添加失败');
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
    
    /**
     *  加载图片列表模板
     */
    public function Image_List($goryid)
    {
        $id = stristr($goryid, '.', true);
        $list = Db::table('albumcategory')
            ->alias('ac')
            ->join('album al', 'ac.id = al.gory_id')
            ->where(['ac.user_id' =>$_SESSION['id'], 'al.gory_id' => $id, 'al.status' => '0'])
            ->select();
        
        return view('album/album-imglist', ['title' => '相册图片列表', 'list' => $list]);
    }
    
    /**
     *  删除图片
     */
    public function Delete_Image($id)
    {
        //获取图片ID
        $aid = stristr($id, '.', true);
        $result = Db::table('album')->where('id', $aid)->delete();
        if ($result) {
            $this->success('删除图片成功');
        } else {
            $this->error('删除图片失败');
        }
    }
    
    
}
