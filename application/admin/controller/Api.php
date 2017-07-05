<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
session_start();
class Api extends Controller
{

    public function index()
    {
        $curl = curl_init();
//        dump($curl);
        $apikey="ec5f2877d268ef3ed571e073f1c30f0a";
        $url="http://api.tianapi.com/qiwen/?key=".$apikey."&num=20";
        curl_setopt($curl, CURLOPT_URL, $url);
        // CURL执行
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curl);


        curl_close($curl);

        $jsonObj = json_decode($data);
// var_dump($jsonObj);
// 提取文章列表
        $newslist = $jsonObj->newslist;
// var_dump($newslist);
        if(empty($_SESSION['adminname'])){
            return view('login/login');
        }
        return view('index/Api', ['title' => '后台娱乐','newslist'=>$newslist]);
    }

}