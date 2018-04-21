<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 19:37
 */

namespace app\api\model;


use app\lib\Exception\UnregisterException;
use app\lib\Exception\WrongPasswordException;
use think\Model;

class Student extends Model
{

    protected $autoWriteTimestamp = true;

    public function notRegistered($username)
    {
        $res = $this->where('student_number', '=', $username)->find();
        if (count($res) == 0) return true;
        else return false;
    }

    public function isExist($username, $password)
    {
        $res_name = $this->where('student_number', '=', $username)->find();

        $res_student = $this->where('student_number', '=', $username)->where('password', '=', $password)->find();

        if (count($res_name) == 0) throw new UnregisterException();

        else if (count($res_student) == 0) throw new WrongPasswordException();

        else return $res_student;
    }

    public function insertStudent($username, $name, $password, $nickname, $college, $specialty, $email, $phone, $question, $answer)
    {
        return $this->data([
            'student_number' => $username,
            'password' => $password,
            'name' => $name,
            'nickname' => $nickname,
            'college' => $college,
            'specialty' => $specialty,
            'email' => $email,
            'phone' => $phone,
            'question' => $question,
            'answer' => $answer
        ])->save();
    }

    public function getQuestion($username)
    {
        $q_res = $this->where('student_number', '=', $username)->find();
        $res['question'] = $q_res->getData('question');
        $res['answer'] = $q_res->getData('answer');
        return $res;

    }

    public function resetPassword($username, $password)
    {
        return $this->where('student_number', '=', $username)->update([
            'password' => $password
        ]);
    }

    public function updateInformation($username, $information)
    {
        return $this->where('student_number', '=', $username)->update($information);

    }
}