<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::any('/validate/test', 'TestController@validateTest');


Route::group(['prefix' => 'service/customer'], function() {
    //用户注册
    Route::any('/register', 'Service\CustomerController@register');
    //用户登录
    Route::any('/login', 'Service\CustomerController@login');
    //用户登出
    Route::any('/logout', 'Service\CustomerController@logout');
    //获取用户信息
    Route::any('/read', 'Service\CustomerController@read');
    //检查用户名是否存在
    Route::any('/exists', 'Service\CustomerController@exists');
});


Route::group(['prefix' => 'service/question'], function() {
    //新增问题
    Route::any('/add', 'Service\QuestionController@add');
    //更新问题
    Route::any('/change', 'Service\QuestionController@change');
    //查看问题
    Route::any('/read', 'Service\QuestionController@read');
    //删除问题
    Route::any('/remove', 'Service\QuestionController@remove');
});


Route::group(['prefix' => 'service/answer'], function() {
    //新增回答
    Route::any('/add', 'Service\AnswerController@add');
    //更新问题
    Route::any('/change', 'Service\AnswerController@change');
    //查看问题
    Route::any('/read', 'Service\AnswerController@read');
});


Route::group(['prefix' => 'service/comment'], function() {
    //添加评论
    Route::any('/add', 'Service\CommentController@add');
    //查看评论
    Route::any('/read', 'Service\CommentController@read');
    //删除评论
    Route::any('/remove', 'Service\CommentController@remove');
});


//通用
Route::group(['prefix' => 'service'], function() {
    //投票
    Route::any('/vote', 'Service\CommonController@vote');
    //查看评论
    Route::any('/timeline', 'Service\CommonController@timeLine');
    //删除评论
    // Route::any('/remove', 'Service\CommentController@remove');
});
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
/**
 * 页面路由，即angular里面定义的路由需要跳转的模板
 */
// Route::get('page/home', function() {
//     return view('page.home');
// });
Route::get('page/login', function() {
    return view('page.login');
});
Route::get('page/signup', function() {
    return view('page.signup');
});
Route::get('question/add', function() {
    return view('question.add');
});

/**
 * 任何以/views/的路由都返回后面的参数所在的视图
 * 因为在angular定义的路由中的templateurl需要后台服务器先定义好
 * 此处批量定义，规定了js中的路由形式
 * 即：templateUrl: 'views/page.home'
 */
Route::get('/views/{name}', function($name) {
    return view($name);
});

/**
 * 前端angular的路由去掉#号之后，当前路由在后台怕匹配不到对应的路由时，
 * 则加载前端ui-view所在的首页
 */
Route::any('{path?}', function () {
    return view('index');
})->where("path", ".+"); // .：用于匹配除换行符之外的所有字符; +： 表示1到多个



