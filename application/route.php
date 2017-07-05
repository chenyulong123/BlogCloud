<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

use think\Route;

//周明珠路由
Route::post('/blog/editLink','index/blog/editLink');
Route::get('/blog/link' , 'index/blog/link');
Route::get('/blog/showlink/:id','index/blog/showlink');
Route::get('/blog/delLink/:id','index/blog/dellink');
Route::get('/blog/delart/:id' , 'index/Blog/delart');
Route::get('/blog/categorydetail/:cate' , 'index/Blog/categorydetail');
Route::get('/blog/index' , 'index/blog/index');
Route::get('/blog/category' , 'index/blog/category');
Route::get('/blog/comment' , 'index/blog/comment');
//转载
Route::get('/blog/reprint/:id','index/blog/reprint');
Route::get('blog/comment_sub' ,'index/blog/comment_sub');
Route::get('/blog/tag' , 'index/blog/tag');
Route::get('/blog/set' , 'index/blog/set');
Route::get('/blog/count' , 'index/blog/count');
Route::get('/blog/allblog' , 'index/blog/allblog');
Route::get('/blog/newblog' , 'index/blog/newblog');
//待审核
Route::get('/blog/checkart' , 'index/blog/checkart');
Route::get('/blog/articleinfo/:id' , 'index/blog/articleinfo');
//编辑日志
Route::get('/blog/editart/:id' , 'index/blog/editart');
//编辑后提交日志
Route::post('/blog/editblog' , 'index/blog/editblog');

Route::get('/blog/deleteArt/:id' , 'index/blog/delArt');
Route::post('/blog/addBlogName' , 'index/blog/addBlogName');
Route::post('/blog/showBlogName' , 'index/blog/showBlogName');
Route::post('/blog/addArt' , 'index/blog/addArt');
Route::post('/blog/addLink','index/blog/addLink');
Route::get('/blog/showlink/:id','index/blog/showlink');

Route::post('/blog/editBlogName','index/Blog/editBlogName');
Route::get('/vip/index' , 'index/Vip/index');
Route::get('/vip/order' , 'index/Vip/order');
Route::post('/vip/addorder' , 'index/Vip/addorder');

Route::get('/blog/showcategory','index/blog/showcategory');

//新增文章点赞路由
Route::get('/article/love','index/index/artlove');
//评论处理
Route::post('/article/addcomment','index/index/addcomment');
//
Route::post('/blog/edittag','index/blog/edittag');
Route::get('/blog/showtag/:id','index/blog/showtag');
Route::get('/blog/showcomment/:id','index/blog/showcomment');
Route::post('/blog/edittag','index/blog/edittag');
Route::get('/blog/delcomment','index/blog/delcomment');


//周明珠路由结束

//陈玉龙路由
Route::post('/user/Upload_img','index/user/Upload_img');
Route::get('/user','index/User/index');
Route::post('/user/Set_Userinfo','index/user/Set_UserInfo');
Route::get('/user/Person_Info','index/user/Person_Info');
Route::get('/user/Person_Mod','index/user/Person_Mod');
Route::get('/user/Person_Passport','index/user/Person_Passport');
Route::get('/user/Person_Secret','index/user/Person_Secret');
Route::get('/UserInfo/User_Info','index/UserInfo/User_Info');
Route::post('/UserInfo/User_ModPass','index/UserInfo/User_ModPass');
Route::post('/UserInfo/Mod_Secret','index/UserInfo/Mod_Secret');
Route::get('/UserInfo/Accept_Message','index/UserInfo/Accept_Message');
Route::get('/UserInfo/Add_Friend_Ag','index/UserInfo/Add_Friend_Ag');
Route::get('/UserInfo/Friend_Del','index/UserInfo/Friend_Del');
Route::get('/UserInfo/API_interface','index/UserInfo/API_interface');

Route::get('/index/login','index/index/login');
Route::get('/index/Log_out','index/index/Log_out');
Route::get('/index','index/index/index');
Route::get('/index/Article','index/index/Article');
Route::get('/index/register','index/index/register');
Route::get('/index/Index_Search','index/index/Index_Search');
Route::get('/index/Article_List','index/index/Article_List');


Route::get('/Album','index/Album/index');
Route::get('/Album/Album_Upload','index/Album/Album_Upload');
Route::post('/Album/Album_Category','index/Album/Album_Category');
Route::get('/Album/Del_AlbumCategory','index/Album/Del_AlbumCategory');
Route::post('/Album/Upload_Image','index/Album/Upload_Image');
Route::get('/Album/Image_List','index/Album/Image_List');
Route::get('/Album/Delete_Image','index/Album/Delete_Image');
Route::get('/Album/Ai_xi','index/Album/Ai_xi');



Route::get('/Friend','index/Friend/index');
Route::get('/Friend/Friend_State','index/Friend/Friend_State');
Route::get('/Friend/Friend_Invite','index/Friend/Friend_Invite');
Route::get('/Friend/Friend_Search','index/Friend/Friend_Search');
Route::post('/Friend/Search_Friend','index/Friend/Search_Friend');
Route::get('/Friend/Search_MayK_Friend','index/Friend/Search_MayK_Friend');
Route::get('/Friend/Add_Friend','index/Friend/Add_Friend');
Route::get('/Friend/Friend_List','index/Friend/Friend_List');
Route::get('/Friend/Delete_Friend','index/Friend/Delete_Friend');
Route::post('/Friend/Private_Message','index/Friend/Private_Message');

//陈玉龙路由结束

//leon路由开始
Route::get('/adminblogdetails','admin/blog/blog_details');

//blog列表页操作方法的路由
Route::get('/adminblogtop','admin/blog/top');
Route::get('/adminblogcomment','admin/blog/comment');
Route::get('/adminblogrecommend','admin/blog/recommend');
Route::get('/adminblogbdisplay','admin/blog/bdisplay');

//评论的路由
Route::get('/admincomment','admin/comment/commentList');
Route::get('/admincommentdisplay','admin/comment/comdisplay');
Route::get('/admincommentdetails','admin/comment/commentdetails');

//博客业的路由
Route::resource('adblog','admin/blog');
Route::get('adblogpage','admin/blog/page');

//相册页路由
Route::get('adimageuid','admin/image/uidimageslist');
Route::get('adimagelist','admin/image/imagesList');
Route::get('adimagedetails','admin/image/imagesDetails');
Route::get('adimgdetstatus','admin/image/imagestatus');

//活动
Route::get('activity','index/advertisement/advertisement');
//leon路由结束

