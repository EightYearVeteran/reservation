<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:28
 */

namespace app\api\controller\v1;


use app\Validate\loginValidate;
use think\Controller;

class Student extends Controller
{

    public function register($username, $password)
    {

    }

    public function login($username, $password)
    {
        (new loginValidate())->checkParameters();

        return ["code" => 20000, "message" => "OK"];

    }
}