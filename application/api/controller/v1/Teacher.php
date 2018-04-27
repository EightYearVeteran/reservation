<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:28
 */

namespace app\api\controller\v1;


use app\lib\Exception\FailMessage;
use app\lib\Exception\SuccessMessage;
use app\lib\Exception\teacher\AlreadyRegisteredException;
use app\lib\Exception\WrongAnswerException;
use app\validate\teacher\RegisterValidate;
use app\validate\teacher\LoginValidate;
use app\api\model\Teacher as TeacherModel;

use app\validate\teacher\UsernameValidate;
use think\Controller;
use think\Session;

class Teacher extends Controller
{
    /**
     * @param string $teacher_number
     * @param string $password
     * @param string $name
     * @param string $nickname
     * @throws AlreadyRegisteredException
     * @throws FailMessage
     * @throws SuccessMessage
     * @throws \app\lib\Exception\teacher\RegisterParameterException
     */
    public function register($teacher_number = '', $password = '', $name = '', $nickname = '')
    {
        (new RegisterValidate())->checkUp();

        $teacherModel = new TeacherModel();

        if ($teacherModel->notRegister($teacher_number)) {
            if ($teacherModel->insertTeacher($teacher_number, $password, $name, $nickname))
                throw new SuccessMessage([
                    'errorMessage' => '注册成功'
                ]);
            else
                throw new FailMessage([
                    'errorMessage' => '注册失败'
                ]);
        } else {
            throw new AlreadyRegisteredException();
        }
    }

    /**
     * @param $teacher_number
     * @param $password
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws WrongAnswerException
     * @throws \app\lib\Exception\Teacher\UnregisterException
     * @throws \app\lib\Exception\teacher\LoginParameterException
     * @throws \app\lib\Exception\teacher\WrongPasswordException
     */
    public function login($teacher_number = '', $password = '')
    {
        (new LoginValidate())->checkUp();
        $teacher_number = (string)$teacher_number;

        if (Session::has($teacher_number)) {

            if (Session::get($teacher_number)->getData('password') == $password)
                return Session::get($teacher_number);
            else
                throw new WrongAnswerException();
        } else {

            $teacherModel = new TeacherModel();
            $res = $teacherModel->isExist($teacher_number, $password);
            Session::set($teacher_number, $res);

            return $res;
        }
    }

    /**
     * @param $teacher_number
     * @throws SuccessMessage
     */
    public function logout($teacher_number = '')
    {
        (new UsernameValidate())->checkUp();
        Session::delete($teacher_number);

        throw new SuccessMessage([
            'errorMessage' => '登出成功'
        ]);
    }

    /**
     * @param array $free_time : the array of the teachers' free time
     */
    public function addFreeTime($free_time)
    {

    }

    /**
     * update the current week free time
     */
    public function updateFreeTime()
    {

    }
}