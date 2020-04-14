<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::rule('/','index/index');

//首页
Route::rule('index/:name','index/:name');
//登录
Route::post('login/:name','login/:name');
//上传
Route::post('upload/:name','upload/:name');
//用户
Route::post('user/:name','user/:name');

//图片输出
Route::rule('image/:name','image/index')->pattern(['name' => '[\d]+\_[\0-9a-z]+(\_[\0-9a-z]+)?(\.[a-z]+)?']);