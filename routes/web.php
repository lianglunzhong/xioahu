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