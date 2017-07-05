<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;
session_start();

class Blog extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
    	$id = $_SESSION['id'];
        $result = Db::name('blog_name')->where('user_id', $id)->select();
        if ($result) {
            //获取文章信息 标题 作者  日期
            $list = Db::name('article')->alias('a')->field('a.id,title,comment_count,author,post_time,category')->join('user', 'a.user_id=user.id')->where('user.id', $id)->paginate(2); 
            $page = $list->render();  
            return view('blog/index', ['title' => '博客管理_博客云', 'list' => $list,'page'=>$page]);
        } else {
            return view('blog/u', ['title' => '博客管理_博客云', 'id' => $id]);
        }
    }
    //增加博客名
    public function addBlogName()
    {
        $p = input('post.');
        
        $data = [
            'name' => $p['name'],
            'domain' => $p['domain'],
            'user_id' => $p['user_id']
        ];
        $row = Db::name('blog_name')->data($data)->insert();
        if ($row > 0) {
            return $this->success('恭喜您,现在可以去写博客啦', url('index/index/index'));
        } else {
            return $this->success('添加失败,请重试');
        }
    }
    //显示博客名
    public function showBlogName(){
        $id = $_SESSION['id'];
        $list = Db::name('blog_name')->where('user_id' , $id)->select();
        if ($list > 0) {
            $info['status'] = true;
            $info['name'] = $list['name'];
            $Info['damain'] = $list['domain'];
        } 
        return json($info);
       }

   

    public function comment()
    {
        $list = Db::name('article')->field('title , comment_count,id')->where('user_id',$_SESSION['id'])->paginate(5);
          $page =  $list->render();
        return view('blog/blog_comment', ['title' => '评论_博客云','list'=>$list,'page'=>$page]);
    }

    public function tag()
    {
        $use_id = $_SESSION['id'];
        $list = Db::name('article')->where('user_id' ,$_SESSION['id'] )->paginate(5);
         $page =  $list->render();
        // $list = Db::query("select tags,count(title) from  article where user_id= $use_id group by tags  ");
        //dump($list);die;
        return view('blog/blog_tag', ['title' => '标签_博客云','list'=>$list,'page'=>$page]);
    }

    public function showcomment($id){

          $result = Db::name('responce')->field('id,text,create_time,author')->where('aid',$id)->limit(5)->select();
   
//dump($result);die;
           if ($result > 0) {
               $info['status'] = true;
              $info['list'] =  $result;
          

           
           } else{
               $info['status'] = false;
               $info['msg'] = '删除失败,请重试';
           }
           return json($info);
       }

    public function delcomment(){
        $id = input();
        $id = implode($id);
        $row = Db::name('responce')->where('id',$id)->delete();

        if ($row > 0) {
            $info['status'] = true;
            $info['msg'] = '删除成功';

        }
        return json($info);
    }
     public function edittag(){

       $p = input('post.');

        $data['tags'] = $p['tag'];
        $data['user_id'] = $_SESSION['id'];
        $id= $p['sub_id'];
        $row = Db::name('article')->where('id',$id)->update($data);
        if ($row > 0) {
            return $this->success('blog/blog_tag');
        }
    }
     
   public function showtag($id){
        
         $result = Db::name('article')->field('tags')->where('id',$id)->select();

          if ($result > 0) {
              $info['status'] = true;
             $info['tag'] = $result[0]['tags'];

              $info['msg'] = '成功删除';
          } else{
              $info['status'] = false;
              $info['msg'] = '删除失败,请重试';
          }
          return json($info);
      }

    public function link()
    {
    	$id = $_SESSION['id'];
    	$list = Db::name('link')->paginate(5);
        $page = $list->render();
        return view('blog/blog_link', ['list' => $list,'title'=>'友情链接_博客云','page'=>$page]);
    }
    //增加友情链接
    public function addLink(){
         
    	$p = input('post.');
     
    	$data = [
    		'linkname' => $p['linkname'],
    		'url' => $p['url'],
    		'user_id' =>$_SESSION['id']
    	];
    	$list = Db::name('link')->data($data)->insert();
    	if ($list > 0) {
            return $this->success('/blog/link');
        }
    }
    //删除友情链接
   public function dellink($id){
        $result = Db::name('link')->where('id',$id)->delete();
        if ($result > 0) {
            $info['status'] = true;
            $info['msg'] = '成功删除';
        } else{
            $info['status'] = false;
            $info['msg'] = '删除失败,请重试';
        }
        return json($info);
    }
     public function showlink($id){
     	
       $result = Db::name('link')->where('id',$id)->select();

        if ($result > 0) {
            $info['status'] = true;
           $info['linkname'] = $result[0]['linkname'];
           $info['url'] = $result[0]['url'];
            $info['msg'] = '成功删除';
        } else{
            $info['status'] = false;
            $info['msg'] = '删除失败,请重试';
        }
        return json($info);
    }
    //编辑友情链接
     public function editLink(){

       $p = input('post.');
        $data['linkname'] = $p['linkname'];
        $data['url'] = $p['url'];
        $data['user_id'] = $_SESSION['id'];
        $id= $p['sub_id'];
        $row = Db::name('link')->where('id',$id)->update($data);
        if ($row > 0) {
            return $this->success('blog/link');
        }
    }
     
    //博客设置
    public function set()
    {
        $list = Db::name('blog_name')->where('user_id',$_SESSION['id'])->find();

        return view('blog/blog_set', ['title' => '设置_博客云','list'=>$list]);
    }
    //更改blog_name
    public function editBlogName(){
        $p = input('post.');
        $data['name'] = $p['name'];
        $data['intro'] = $p['description'];
        $data['pwd'] = $p['accessPwd'];
        $row = Db::name('blog_name')->where('user_id',$_SESSION['id'])->update($data);
        if ($row > 0) {
            return $this->success('更新成功' );
        }
    }

    public function count()
    {
        $user_id = $_SESSION['id'];
        $count = Db::name('article')->where('user_id' ,$user_id)->order('count','desc')->limit(5)->select();
        $comment = Db::name('article')->where('user_id' ,$user_id)->order('comment_count','desc')->limit(5)->select();
        return view('blog/blog_vistcount', ['title' => '访客统计_博客云','count'=>$count,'comment'=>$comment]);
    }

    public function allblog()
    {
        return view('blog/allblog', ['title' => '全部博客_博客云']);
    }

    public function newblog()
    {
        return view('blog/newblog', ['title' => '写新日志_博客云']);
    }

    //删除文章
    public function delart($id)
    {
        $row = Db::name('article')->where('id',$id)->delete();
        if ($row > 0) {
        	$info['status'] = true;
        	$info['msg'] = '文章已经被删除';
        } else{
        	$info['status'] = false;
        	$info['msg'] = '删除失败,请重试';
        }
        return json($info);
    }

    //增加文章
    public function addArt(Request $request)
       {	
            //历史上今天api接口

            $num = 10; //返回数量
            $key ='e105351421c59912a929e7762f930ddb';
            $url = "http://api.tianapi.com/txapi/lishi/?key=".$key."&num=".$num."&rand";     //API接口
            $ch = curl_init();  
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($file_contents,true);
            $json = $json['newslist'];
            //接受新增文章数据
       		$p = input('post.');
       	    $data = [
                'user_id' => $_SESSION['id'],
                'title' =>$p['title'],
                'author' => $_SESSION['name'],
                'type' => $p['type'],
                'tags' => $p['tags'],
                'content'=>$p['content'],
                'post_time' => $p['post_time'],
                'category' =>$p['category'],
                'allow_recommend' => empty($p['allow_recommend'])?0:1,
                'always_top' => empty($p['always_top'])?0:1,
                'allow_comment' => empty($p['allow_comment'])?0:1,
                'allow_trackback' => empty($p['allow_trackback'])?0:1
            ];
            //返回自增主键id
            $id = Db::name('article')->insertGetId($data);
            if ($id > 0) {
                $list = Db::name('article')->where('id',$id)->find();
                return view('blog/articleinfo',['title'=>'个人博客','list'=>$list]);
            } else{
                echo '加载失败';
            }
       }
    //显示文章到个人文章
    public function articleinfo($id){

            $num = 10; //返回数量
            $key ='e105351421c59912a929e7762f930ddb';
            $url = "http://api.tianapi.com/txapi/lishi/?key=".$key."&num=".$num."&rand";     //API接口
            $ch = curl_init();  
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($file_contents,true);
            $json = $json['newslist'];

        
             //获取文章点击次数 点击次数+1

           $row = Db::name('article')->where('id' , $id)->setInc('count');
          
            //查询文章具体信息
            $list = Db::name("article")->find($id);



        
            return view('blog/articleinfo', ['title' => '用户文章','list'=>$list,'json'=>$json]);
    }

    public function category()
       {
            $result = Db::name('category')->select();
            $arr = [];
            foreach ($result as  $value) {
                if ($value['pid'] > 0) {
                    $arr[] = $value;
                }
            }
            $id = $_SESSION['id'];
            // $list = Db::name('article')->alias('a')->field('author,post_time,category')->join('user', 'a.user_id=user.id')->where('user_id', $id)->where('category',$arr[0]['category_name'])->paginate(2); 
            // $list = Db::name('article')->alias('a')->field('category')->group('category')->where('user_id', $id)->select();
            $list = Db::query("select category,count(title) from  article where user_id=$id group by category  ");
            // $list = Db::name('article')->field('category',count('title'))->where('user_id' , $id)->group('category')->select();
           
      
           return view('blog/blog_category', ['title' => '分类_博客云','list'=>$list]);
       }

        public function showcategory(){
            $list = Db::name('category')->select();
            $arr = [];
            foreach ($list as  $value) {
                if ($value['pid'] > 0) {
                    $arr[] = $value;
                }
            }
                return json($arr);
        }
        public function categorydetail($cate){
           
            $list = Db::name('article')->where('category',$cate)->paginate(5);
            $count = count($list);
            $page =  $list->render();

            return view('blog/catedetail' , ['title'=>'博文列表_博客园','list'=>$list,'cate'=>$cate,'count'=>$count,'page'=>$page]);
        }
        public function editart($id){

            
       
            $list = Db::name('article')->select($id);

            return view('blog/editblog',['title'=>'编辑日志','list'=>$list[0],'id'=>$id]);
        }
        public function editblog(){
                    $p = input('post.');
                    $id = $p['art_id'];    
                 //var_dump($data);
                 $row = Db::name('article')->where('id' ,$id)->update(
                    [
                    'title'=>$p['title'],
                    'tags' => $p['tags'],
                    'content'=>$p['content'],
                    'edit_time' => $p['edit_time'],
                    'allow_recommend' => empty($p['allow_recommend'])?0:1,
                    'always_top' => empty($p['always_top'])?0:1,
                    'allow_comment' => empty($p['allow_comment'])?0:1,
                    'allow_trackback' => empty($p['allow_trackback'])?0:1

                    ]
                    );
                 if ($row >0) {
                    //return view('index/article',['list'=>$data,'title'=>'用户文章']);
                     return $this->success('恭喜您,编辑成功' ,'index');
                 } else{
                     echo '加载失败';
                 }
            }

            //待审核文章
            public function checkart(){
                $id = $_SESSION['id'];
                $list = Db::name('article')->alias('a')->field('a.id,title,author,post_time,category')->join('user', 'a.user_id=user.id')->where('user.id', $id)->where('check',0)->select();  
                return view('blog/checkart', ['title' => '博客管理_博客云', 'list' => $list]);
            }

            //转载
            public function reprint($id){
                
                $list = DB::name('article')->find($id);
                $data = [
                'user_id' => $_SESSION['id'],
                'title' => '[转载]'.$list['title'],
                'from_author' => $list['author'],
                'author' => $_SESSION['name'],
                'type' => 0,
                'tags' => '转载',
                'post_time' => date('Y-m-d H:i:s'),
                'content' => $list['content'],
                'category' => $list['category'],
                'always_top' => $list['always_top'],
                'allow_recommend' => $list['allow_recommend'],
                'allow_comment' => $list['allow_comment'],
                'allow_trackback' => $list['allow_trackback'],
                'rep_count' => 0
                ];

                $row = Db::name('article')->data($data)->insert();
                //dump($row);
                if ($row > 0) {
                     $res = Db::name('article')->where('id' , $id)->setInc('rep_count'); 
                    $count = Db::name('article')->field('rep_count')->find($id);
                   $count = implode($count);
                    $info['count'] =  $count;
                    $info['status'] = true;
                    $info['msg'] ='博文已转载成功!';
                }
                return json($info);
                
            }
        

}



