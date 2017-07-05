<?php
namespace app\Index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Validate;

session_start();

class Index extends Controller
{
    /**
     * @return 主页模板
     */
    
    public function index()
    {
        if (empty($_SESSION['name'])) {
            $_SESSION['name'] = '游客';
        }
        
        //分页
        //计算总条数
        $allpage = Db::table('album')->field('id')->select();
        $num = count($allpage);
        //计算有多少页
        $evepage = 30;
        $numpage = ceil($num / $evepage);
        
        //获取当前的页码
        $page = Request::instance()->get();
        if (empty($page['page'])) {
            $page['page'] = 1;
        }
        $page_row = $page['page'];
        
        //限制最大最小页码
        $page['page'] = floor(max($page, 1));
        $page['page'] = floor(min($page, $numpage));
        
        //计算 偏移量     分页从第几行开始查
        $offset = ($page_row - 1) * $evepage;
        
        //求上一页 和 下一页
        $prev = $page_row - 1;
        $next = $page_row + 1;
        $prev = max($prev, 1);
        $next = min($next, $numpage);
        //查询结果
      
        $row = Db::query("select u.id,al.name,u.name as username,ui.icon 
from album al,user u,userinfo ui where al.user_id = u.id and u.name = ui.name 
limit {$offset}, {$evepage}");
        
        
        //动态生成 页码连接
        // var_dump($_SERVER); die;
        $num_link = '';
        $url = $_SERVER['SCRIPT_NAME'] . '?page=';
        for ($i = 1; $i <= $numpage; $i++) {
            $num_link .= '<a  class="page pc" href="' . $url . $i . '"> ' . $i . ' </a>';
        }
        
        //拼接 页码html显示代码
        $str = <<<XO
    <a class='page pc' href='{$url}{$prev}'>上一页</a>
    {$num_link}
    <a class='page pc' href='{$url}{$next}'>下一页</a>
XO;
        
        //栏目
        $column = Db::table('category')->where('pid', 0)->field('id, path, category_name')->select();
        $t_column = Db::table('category')->where('pid', 'GT', 0)->field('category_name')->select();
                
        return view('index/index', ['title' => 'BlogBur', 'data' => $row, 'pagelist' => $str, 'column' => $column, 't_column' => $t_column]);
        
    }
    
    /**
     * @return 注册页模板
     */
    public function register()
    {
        return view('index/register', ['title' => '用户注册']);
    }
    
    /**
     * @return 登陆页模板
     */
    public function Login()
    {
        return view('index/login', ['title' => '用户登陆']);
    }
    
    /**
     * @return 文章页模板
     */
    public function Article($list_id)
    {
        $art_id = Request::instance()->get();
  
        $id = implode($art_id);
   
        $aid = stristr($id ,'.',true);

        //历史上今天api接口
    
        $num = 10; //返回数量
    
        $key = 'e105351421c59912a929e7762f930ddb';
        $url = "http://api.tianapi.com/txapi/lishi/?key=" . $key . "&num=" . $num . "&rand";     //API接口
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($file_contents, true);
        $json = $json['newslist'];
    
        //获取文章点击次数 点击次数+1
        $row = Db::name('article')->where('id', $aid)->setInc('count');
    
        //查询文章具体信息
        $list = Db::name("article")->field('id,title,author,tags,post_time,content,count,love,rep_count,comment_count')->find($aid);
    
        //评论查询 调用方法
        $comment = $this->commentlist();
    
        foreach ($comment as $v) {
            if ($v['aid'] == $aid) {
                $commentlist[] = $v;
            } 
        }
     

        //查询友情链接
        $link = Db::name('link')->where('user_id' ,$_SESSION['id'])->select();
    
        //栏目
        $columns = Db::table('category')->where('pid', 0)->field('id, path, category_name')->select();
        $t_columns = Db::table('category')->where('pid', 'GT', 0)->field('category_name')->select();
        return view('index/article',
            ['title' => '用户文章',
                'list' => $list,
                'json' => $json,
                'column' => $columns,
                't_column' => $t_columns,
                'commentlist'=>$commentlist
            ]);

    }
    
    //处理评论数据 遍历
    public function commentlist($pid=0, &$commentList=array(),$spac=0 ,$pauthor=null){
        static $i = 0;
        $spac = $spac +1; //初始为1级评论
        $pauthor = $pauthor;
        $List = Db::name('responce')->field('author ,id,aid,pid,text,create_time')->where(array('pid'=>$pid))->where('display',1)->select();
        foreach ($List as $k => $v) {
            $commentList[$i]['level']=$spac;//评论层级
            $commentList[$i]['author']=$v['author'];
            $commentList[$i]['id']=$v['id'];
            $commentList[$i]['aid']=$v['aid'];
            $commentList[$i]['pid']=$v['pid'];//此条评论的父id
            $commentList[$i]['text']=$v['text'];
            $commentList[$i]['create_time']=$v['create_time'];
            $commentList[$i]['pauthor']=$pauthor;
            $i++;
            //递归调用自己
            $this->CommentList($v['id'],$commentList,$spac,$v['author']);
        }
        
        return $commentList;
    }
    //添加评论
    public function addcomment(){
        $p = input('post.');
        $data = [
            'author' =>$p['author'],
            'pid' =>$p['pid'],
            'text' =>$p['comment'],
            'aid' =>$p['aid'],
            'create_time' => date('Y/m/d H:i:s'),
            'display' => 1
        ];
        $row  =Db::name('responce')->data($data)->insert();
        if ($row > 0) {
            //如果添加成功 comment_count 字段 加1
            $res = Db::name('article')->where('id' , $p['aid'])->setInc('comment_count');
            
            return $this->success('评论成功');
        }
    }
    
    /**
     *  登陆验证
     */
    public function Login_Validate()
    {
        $request = Request::instance()->post();
        $pass = Db::table('user')->where('name', $request['name'])->select();
        if (!empty($pass)) {
            $final = Db::table('userinfo')->where('name', $request['name'])->select();
    
            $name = $pass[0]['name'];
            $id = $pass[0]['id'];
    
            if (!empty($final[0]['icon'])) {
                $icon = $final[0]['icon'];
            } else {
                $icon = '';
            }
    
    
            if ($request['password'] === $pass[0]['password']) {
                $_SESSION['name'] = $name;
                $_SESSION['id'] = $id;
                $_SESSION['icon'] = $icon;
                $this->success('登陆成功', '/index');
            } else {
                $this->error('登陆失败');
            }
        } else {
            $this->error('该用户不存在');
        }
        
        
    }
    
    /**
     * @return 登出
     */
    public function Log_out()
    {
        $_SESSION['name'] = '';
        $_SESSION['icon'] = '';
        $_SESSION['id'] = '';
        $this->success('退出成功');
    }
    
    /**
     *  注册验证
     */
    public function Register_Validate()
    {
        $request = Request::instance()->post();
        
        //正则验证表单数据
        $msg = [
            'name' => '名称不合法',
            'password' => '密码必须6位，字母数字组合',
            'confirm' => '两次密码不一致',
            'tel' => '电话格式错误'
        ];
        $validate = new Validate([
            'name' => '/^[A-Za-z0-9]+$/',
            'password' => '/^[a-z0-9]{6}$/',
            'confirm' => 'confirm:password',
            'tel' => ['regex' => '/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/']
        ], $msg);
        if ($validate->check($request)) {
            //验证验证码是否正确
            if ($request['validate'] != $_SESSION['validate']) {
                dump('验证码输入错误');
            }
            
            $data = ['name' => $request['name'], 'password' => $request['password'], 'tel' => $request['tel']];
            
            $_SESSION['name'] = $request['name'];
            
            //用户信息写入数据库
            $result = Db::table('user')->insert($data);
            $id = Db::name('user')->getLastInsID();
            $data_info = ['name' => $request['name']];
            $final = Db::table('userinfo')->insert($data_info);
            if ($result && $final) {
                $_SESSION['id'] = $id;
                return view('user/mange-msg', ['title' => '消息', 'username' => $_SESSION['name']]);
            } else {
                $this->error('注册失败');
            }
        } else {
            $this->error('注册失败');
        }
        
    }
    
    //文章点赞
    public function artlove()
    {
        $request = Request::instance();
        $ip = $request->ip(); // 获取用户ip
        $id = input('id');
        //if(!isset($id) || empty($id)) exit;
        $row = Db::name('art_ip')->where('ip', $ip)->where('art_id', $id)->select();
        
        if (empty($row)) {//如果没有记录
            $res = Db::name('article')->where('id', $id)->setInc('love'); //更新article love字段数据
            $result['love'] = Db::name('article')->find($id);
            $ipdata = [
                'ip' => $ip,
                'art_id' => $id
            ];
            $list = Db::name('art_ip')->data($ipdata)->insert();//插入数据
            $result = Db::name('article')->where('id', $id)->select();
            
            $info['count'] = $result[0]['love'];
            $info['status'] = true;

    	}else{
    		$info['msg'] = '您已经点赞过了';
    		$info['status'] =false;
    	}
        

    	return json($info);
    }
 
   
        
 
    
    public function Index_Search()
    {
        //分页
        //计算总条数
        $allpage = Db::table('album')->field('id')->select();
        $num = count($allpage);
        //计算有多少页
        $evepage = 5;
        $numpage = ceil($num / $evepage);
    
        //获取当前的页码
        $page = Request::instance()->get();
        if (empty($page['page'])) {
            $page['page'] = 1;
        }
        $page_row = $page['page'];
        $keyword = $page['keyword'];
        //限制最大最小页码
        $page['page'] = floor(max($page, 1));
        $page['page'] = floor(min($page, $numpage));
    
        //计算 偏移量     分页从第几行开始查
        $offset = ($page_row - 1) * $evepage;
    
        //求上一页 和 下一页
        $prev = $page_row - 1;
        $next = $page_row + 1;
        $prev = max($prev, 1);
        $next = min($next, $numpage);
        //查询结果
        $row = Db::query("SELECT u.id,al.name,u.name AS username,ui.icon FROM album al,user u,userinfo ui WHERE al.user_id = u.id AND u.name = '{$keyword}' AND ui.name = u.name limit {$offset}, {$evepage}");
    
        //动态生成 页码连接
        // var_dump($_SERVER); die;
        $num_link = '';
        
        $url = '/index/Index_Search' . '?page=';
        for ($i = 1; $i <= $numpage; $i++) {
            $num_link .= '<a  class="page pc" href="' . $url . $i .'&keyword='.$keyword. '"> ' . $i . ' </a>';
        }
    
        //拼接 页码html显示代码
        $str = <<<XO
    <a class='page pc' href='{$url}{$prev}&keyword={$keyword}'>上一页</a>
    {$num_link}
    <a class='page pc' href='{$url}{$next}&keyword={$keyword}'>下一页</a>
XO;
    
        //栏目
        $column = db('category')->where('pid', 0)->field('id, path, category_name')->select();
        $t_column = db('category')->where('pid', 'GT', 0)->field('category_name')->select();
    
        return view('index/index', ['title' => 'BlogBur', 'data' => $row, 'pagelist' => $str, 'column' => $column, 't_column' => $t_column]);

    }

    /**
     *  文章列表页
     */
    public function Article_List($au_id)
    {
        $id = stristr($au_id, '.', true);
        $rows = Db::table('article')->where('user_id', $id)->field('title, post_time, tags,id')->select();
        $art_name = Db::table('user')->where('id', $id)->field('name')->find();
        return view('person/personal',['art_data' => $rows, 'title' => $art_name['name']]);
    }

    
}
