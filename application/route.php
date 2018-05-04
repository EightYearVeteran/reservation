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
Route::get("api/:version/student/get_teacher_freetime", "api/:version.Student/queryTeacherFreeTime");
Route::post("api/:version/student/reserve_teacher", "api/:version.Student/queryTeacherFreeTime");

// teacher
Route::post("api/:version/teacher/register", "api/:version.Teacher/register");
Route::post("api/:version/teacher/login", "api/:version.Teacher/login");
Route::post("api/:version/teacher/logout", "api/:version.Teacher/logout");
Route::get("api/:version/teacher/get_occupied_places", "api/:version.Teacher/queryOccupiedPlaces");
Route::post("api/:version/teacher/apply_for_reserving", "api/:version.Teacher/applyForReserving");
Route::get("api/:version/teacher/get_all_places", "api/:version.Teacher/getAllPlaces");

//common
Route::get("api/:version/common/get_all_specialties", "api/:version.Common/getAllSpecialties");
Route::get("api/:version/common/get_all_teachers", "api/:version.Common/getAllTeachers");
