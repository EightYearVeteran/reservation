<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:28
 */

namespace app\api\controller\v1;


use app\lib\Exception\ParameterException;
use app\lib\Exception\WrongPasswordException;
use think\Controller;
use think\Loader;
use think\Validate;
use think\Session;
use app\Validate\loginValidate;
use app\lib\Exception\UnregisterException;
use app\api\model\Student as StudentModel;

class Student extends Controller
{

    public function register($username, $password)
    {

    }

    /**
     * @param $username
     * @param $password
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws UnregisterException
     * @throws WrongPasswordException
     * @throws \app\lib\Exception\ParameterException
     */
    public function login($username = '', $password = '')
    {
        (new loginValidate())->checkUp();

        $username = (string)$username;

        if (Session::has($username)) { // search from session firstly

            if (Session::get($username)->getData('password') == $password)
                return Session::get($username);
            else
                throw new WrongPasswordException();

        } else { // if cannot find username in session

            $studentModel = new StudentModel();
            $res = $studentModel->isExist($username, $password);
            $student_number = $res->getData('student_number');

            Session::set($student_number, $res);
            \session(['name' => $student_number, 'expire' => 1]); // set session expire time but not work

            return $res;

        }

    }
}