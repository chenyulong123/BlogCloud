<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
session_start();

class Vip extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {     
        return view('vip/index',['title'=>'VIP申请_博客云']);
    }
    public function addorder(){
        $p = input('post.');
        //dump($p);die;
        $data = [
            'ordernum' => $_SESSION['id'].uniqid(),
            'contact' => $p['contact'],
            'tel' => $p['tel'],
            'prdtid' => $p['prdtid'],
            'user_id' =>$_SESSION['id'],
            'time' => date('Y/m/d H:i:s'),
            'status' => 1
        ];
        $list = Db::name('order')->data($data)->insert();
        if ($list > 0) {
            return $this->success('恭喜您成功开通VIP服务');
        }

    }

      public function order()
    {
        //$list = Db::name('order')->select();
         $list = Db::name('order')->field('ordernum , year,price , time ,status')->join('vip' , 'vip.id = order.prdtid' )->where('user_id' , $_SESSION['id'])->select();

         return view('vip/order',['title'=>'订单查询_博客云','list'=>$list]);
    }

    
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
