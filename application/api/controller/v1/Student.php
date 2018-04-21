<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:28
 */

namespace app\api\controller\v1;


use app\lib\Exception\AlreadyRegisteredException;
use app\lib\Exception\NotFoundException;
use app\lib\Exception\WrongPasswordException;
use app\lib\Exception\UnregisterException;
use app\lib\Exception\MustLoginException;
use app\lib\Exception\SuccessMessage;
use app\lib\Exception\FailMessage;

use app\Validate\CheckQuestionParameterValidate;
use app\Validate\LogoutValidate;
use app\Validate\RegisterValidate;
use app\Validate\LoginValidate;
use app\Validate\UsernameValidate;

use app\api\model\Student as StudentModel;
use think\Controller;
use think\Session;

//define('RIGHT_ANSWER', 1);
// session
/// username => username information
/// username_answer => the answer of reset forget question
/// username_answer_result => record the true or false if the answer is right or not

class Student extends Controller
{
//    const RIGHT_ANSWER = 1;

    /**
     * @param string $username
     * @param string $name
     * @param string $password
     * @param string $nickname
     * @param string $college : front end give the student selective items of the college
     * @param string $specialty : front end give the student selective items of the specialty
     * @param string $email : check the format of email
     * @param string $phone : check the length of phone
     * @param string $question
     * @param string $answer
     * @throws AlreadyRegisteredException
     * @throws SuccessMessage
     * @throws \app\lib\Exception\RegisterParameterException
     */
    public function register($username = '', $name = '', $password = '', $nickname = '', $college = '', $specialty = '', $email = '', $phone = '', $question = '', $answer = '')
    {
        (new RegisterValidate())->checkUp();

        $studentModel = new StudentModel();

        //TODO: 用户自定义头像上传
        if ($studentModel->notRegistered($username)) {
            if ($studentModel->insertStudent($username, $name, $password, $nickname, $college, $specialty, $email, $phone, $question, $answer)) // return the number of inert items
                throw new SuccessMessage();
        } else {
            throw new AlreadyRegisteredException();
        }

    }

    /**
     * @param $username
     * @param $password
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     * @throws UnregisterException
     * @throws WrongPasswordException
     * @throws \app\lib\Exception\LoginParameterException
     */
    public function login($username = '', $password = '')
    {
        (new LoginValidate())->checkUp();

        $username = (string)$username; // student_number changes into string

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
//            \session(['name' => $student_number, 'expire' => 1]); // set session expire time but not work
            return $res;
        }
    }

    /**
     * @param string $username
     * @throws SuccessMessage
     * @throws \app\lib\Exception\UsernameParameterException
     */
    public function logout($username = '')
    {
        (new UsernameValidate())->checkUp();
        Session::delete($username);
        throw new SuccessMessage([
            'errorMessage' => '登出成功'
        ]);
    }

    /**
     *
     * forget password and reset the password
     * reset password by checking question and answer or verifying the email
     * 1. get the question
     * 2. submit the answer of the question
     * 3. verify the submitted answer is right or not
     * 4. if the answer is right return success message, if not return fail message
     *
     * @param string $username
     * @param string $password
     * @throws FailMessage
     * @throws SuccessMessage
     * @throws \app\lib\Exception\LoginParameterException
     */
    public function resetForgetPassword($username = '', $password = '')
    {
        (new LoginValidate())->checkUp();

        $studentModel = new StudentModel();

        if (Session::get($username . 'answer_result')) { // the answer is right
            if ($studentModel->resetPassword($username, $password)) {
                throw new SuccessMessage([
                    'errorMessage' => '重置密码成功'
                ]);
            } else {
                throw new FailMessage([
                    'errorMessage' => '重置密码失败'
                ]);
            }

        } else {
            throw new FailMessage([
                'errorMessage' => '重置密码失败'
            ]);
        }
    }

    /**
     * forget password and reset password needs getting forgotten question firstly
     * query from database and get the question and answer
     * put the answer into the session so that it can avoid duplicate queries
     * @param string $username
     * @return array
     * @throws NotFoundException
     * @throws \app\lib\Exception\UsernameParameterException
     */
    public function getForgetQuestion($username = '')
    {

        (new UsernameValidate())->checkUp();

        $studentModel = new StudentModel();

        $q_a = $studentModel->getQuestion($username);

        if (empty($q_a)) throw new NotFoundException();

        Session::set($username . 'answer', $q_a['answer']);

        return ['question' => $q_a['question']];


    }

    /**
     * check the answer compared to the answer that is in the session
     * @param string $username
     * @param string $answer
     * @throws FailMessage
     * @throws SuccessMessage
     * @throws \app\lib\Exception\WrongAnswerException
     */
    public function checkAnswer($username = '', $answer = '')
    {
        // TODO: 参数验证器有问题
        (new CheckQuestionParameterValidate())->checkUp();

        if (Session::has($username . 'answer')) {
            if (Session::get($username . 'answer') == $answer) {
                Session::set($username . 'answer_result', true);
                throw new SuccessMessage(['errorMessage' => '验证成功']);
            } else {
                throw new FailMessage(['errorMessage' => '验证失败']);
            }
        } else {
            throw new FailMessage(['errorMessage' => '验证失败']);
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @throws FailMessage
     * @throws MustLoginException
     * @throws SuccessMessage
     */
    public function resetPassword($username = '', $password = '')
    {
        if (Session::has($username)) { // student login status
            $studentModel = new StudentModel();
            if ($studentModel->resetPassword($username, $password)) {
                throw new SuccessMessage([
                    'errorMessage' => '重置密码成功'
                ]);
            } else {
                throw new FailMessage([
                    'errorMessage' => '重置密码失败'
                ]);
            }

        } else {
            throw new MustLoginException();
        }
    }


    /**
     * @param string $username
     * @param string $name
     * @param string $nickname
     * @param string $college
     * @param string $specialty
     * @param string $email
     * @param string $phone
     * @param string $question
     * @param string $answer
     * @throws FailMessage
     * @throws MustLoginException
     * @throws SuccessMessage
     * @throws \app\lib\Exception\UsernameParameterException
     */
    public function updateInformation($username = '', $name = '', $nickname = '', $college = '', $specialty = '', $email = '', $phone = '', $question = '', $answer = '')
    {
        (new UsernameValidate())->checkUp();
        if (Session::has($username)) { // student  login status
            if (!empty($name)) $information['name'] = $name;
            if (!empty($nickname)) $information['nickname'] = $nickname;
            if (!empty($college)) $information['college'] = $college;
            if (!empty($specialty)) $information['specialty'] = $specialty;
            if (!empty($email)) $information['email'] = $email;
            if (!empty($phone)) $information['phone'] = $phone;
            if (!empty($question)) $information['question'] = $question;
            if (!empty($answer)) $information['answer'] = $answer;

            $studentModel = new StudentModel();
            if ($studentModel->updateInformation($username, $information))
                throw new SuccessMessage([
                    'errorMessage' => '修改信息成功'
                ]);
            else
                throw new FailMessage([ // the same as the old information
                    'errorMessage' => '修改信息失败'
                ]);
        } else { // not login status
            throw new MustLoginException();
        }

    }

    /**
     * students get their information after login
     * the information was recorded in the session util the students login
     * @param string $username
     * @return mixed
     * @throws MustLoginException
     * @throws \app\lib\Exception\UsernameParameterException
     */
    public function getInformation($username = '')
    {
        (new UsernameValidate())->checkUp();

        if (Session::has($username) != null) { // already login status
            return Session::get($username);
        } else { // must login firstly
            throw new MustLoginException();
        }
    }
}