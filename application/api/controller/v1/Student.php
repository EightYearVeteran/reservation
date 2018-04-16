<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:28
 */

namespace app\api\controller\v1;


use app\lib\Exception\WrongPasswordException;
use app\Validate\RegisterValidate;
use think\Controller;
use think\Session;
use app\Validate\LoginValidate;
use app\lib\Exception\UnregisterException;
use app\lib\Exception\SuccessMessage;
use app\api\model\Student as StudentModel;


class Student extends Controller
{
    /**
     * @param $username
     * @param $name
     * @param $password
     * @throws SuccessMessage
     * @throws WrongPasswordException
     * @throws \app\lib\Exception\BaseException
     * @throws \app\lib\Exception\NullPasswordException
     * @throws \app\lib\Exception\NullUsernameException
     * @throws \app\lib\Exception\WrongUsernameException
     * 防止重复注册
     */
    public function register($username, $name, $password)
    {
        // TODO:查询学号是否已经存在, 学号唯一标识学生

        (new RegisterValidate())->checkUp();

        $studentModel = new StudentModel();

        if ($studentModel->insertStudent($username, $name, $password)) // 返回成功插入条数
            throw new SuccessMessage();

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
        (new LoginValidate())->checkUp();

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