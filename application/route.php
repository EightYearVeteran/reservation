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


use think\Route;

// student
Route::post("api/:version/student/register", "api/:version.Student/register");
Route::post("api/:version/student/login", "api/:version.Student/login");
Route::post("api/:version/student/update_information", "api/:version.Student/updateInformation");
Route::post("api/:version/student/get_forget_question", "api/:version.Student/getForgetQuestion");
Route::post("api/:version/student/check_answer", "api/:version.Student/checkAnswer");
Route::post("api/:version/student/reset_forget_password", "api/:version.Student/resetForgetPassword");
Route::post("api/:version/student/reset_password", "api/:version.Student/resetPassword");
Route::post("api/:version/student/logout", "api/:version.Student/logout");

// teacher
Route::post("api/:version/teacher/register", "api/:version.Teacher/register");
Route::post("api/:version/teacher/login", "api/:version.Teacher/login");
Route::post("api/:version/teacher/logout", "api/:version.Teacher/logout");