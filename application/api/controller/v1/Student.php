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

use app\validate\CheckQuestionParameterValidate;
use app\validate\LogoutValidate;
use app\validate\RegisterValidate;
use app\validate\LoginValidate;
use app\validate\UsernameValidate;

use app\api\model\Student as StudentModel;
use app\api\model\FreetimeFreeplace as FreetimeFreeplaceModel;
use app\api\model\StudentTeacher as StudentTeacherModel;

use think\Controller;
use think\Request;
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
     * @throws FailMessage
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
            $res = $studentModel->isExist($username, $password); // $username = $student_number
//            $student_number = $res->getData('student_number');

            Session::set($username, $res);
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

    /**
     * @param $teacher_id
     * @return mixed
     *
     * 查询指定老师的空闲时间
     */
    public function queryTeacherFreeTime($teacher_id)
    {
        return (new FreetimeFreeplaceModel())->queryItem($teacher_id);
    }

    public function reserveTeacher()
    {
        //TODO: 达到最大学生数量就不能再预约, 2个操作: ① 修改freeplace_freetime表中的current_student值 ② 向student_teacher表添加一条预约记录
        $params = Request::instance()->post();
        $timeplaceModel = new FreetimeFreeplaceModel();
        $studentTeacherModel = new StudentTeacherModel();
        $errmsg = array();

        foreach ($params as $item) {
            $freeplace_freetime_id = $item['freeplace_freetime_id'];
            $teacher_id = $item['teacher_id'];
            $student_id = $item['student_id'];

            $res = $timeplaceModel->queryItem($freeplace_freetime_id, true)->getData();

            $max = $res['max_student'];
            $current = $res['current_student'];

            if ($current == $max) {
                array_push($errmsg, ['errorMessage' => 'freeplace_freetime_id为' . $freeplace_freetime_id . '教工号为' . $teacher_id . '已达到最多预约人数']);
                continue;
            }


            if ($timeplaceModel->updateCurrentNum($freeplace_freetime_id, ++$current)) { // 如果添加人数成功就添加预约记录
                $studentTeacherModel->insertItem($student_id, $teacher_id, $freeplace_freetime_id);
            } else { // 否则记录错误信息
                array_push($errmsg, ['errorMessage' => 'freeplace_freetime_id为' . $freeplace_freetime_id . '教工号为' . $teacher_id . '预约失败']);
            }
        }

        if (empty($errmsg))
            throw new SuccessMessage(['errorMessage' => '预约成功']);
        else
            throw new FailMessage(['errorMessage' => $errmsg]);


//        if ($studentTeacherModel->insertItem($student_id, $teacher_id, $freeplace_freetime_id))
//            throw new SuccessMessage([
//                'errorMessage' => '预约成功'
//            ]);
//        else
//            throw new FailMessage([
//                'errorMessage' => '预约失败'
//            ]);
    }

    /**
     * @param $student_id
     * @return mixed
     */
    public function queryReservation($student_id)
    {
        return (new StudentTeacherModel())->queryItem($student_id, true);
    }

    public function cancelReservation()
    {
        $params = Request::instance()->post();
        $timeplaceModel = new FreetimeFreeplaceModel();
        $studentTeacherModel = new StudentTeacherModel();
        $errmsg = array();

        foreach ($params as $item) {
            $freeplace_freetime_id = $item['freeplace_freetime_id'];
            $teacher_id = $item['teacher_id'];
            $student_id = $item['student_id'];

            $res = $timeplaceModel->queryItem($freeplace_freetime_id, true)->getData();
            $current = $res['current_student'];

            if ($current == 0)
                continue;

            if ($timeplaceModel->updateCurrentNum($freeplace_freetime_id, --$current)) { // 如果添加人数成功就添加预约记录
                $studentTeacherModel->deleteItem($student_id, $teacher_id, $freeplace_freetime_id);
            } else { // 否则记录错误信息
                array_push($errmsg, ['errorMessage' => 'freeplace_freetime_id为' . $freeplace_freetime_id . '教工号为' . $teacher_id . '取消预约失败']);
            }

        }

        if (empty($errmsg))
            throw new SuccessMessage(['errorMessage' => '取消预约成功']);
        else
            throw new FailMessage(['errorMessage' => $errmsg]);

//            if ((new StudentTeacherModel())->deleteItem($student_id, $teacher_id, $freeplace_freetime_id))
//            throw new SuccessMessage(['errorMessage' => '取消预约成功']);
//        else
//            throw new FailMessage(['errorMessage' => '取消预约失败']);
    }

    public function updateComment($student_id, $teacher_id, $freeplace_freetime_id, $comment)
    {
        if ((new StudentTeacherModel())->updateComment($student_id, $teacher_id, $freeplace_freetime_id, $comment)) {
            throw new SuccessMessage(['errorMessage' => '评论成功']);
        } else {
            throw new FailMessage(['errorMessage' => '评论失败']);
        }
    }

}