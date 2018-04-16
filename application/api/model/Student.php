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

    public function isExist($username, $password)
    {
        $res_name = $this->where('student_number', '=', $username)->find();

        $res_student = $this->where('student_number', '=', $username)->where('password', '=', $password)->find();

        if (count($res_name) == 0) throw new UnregisterException();

        else if (count($res_student) == 0) throw new WrongPasswordException();

        else return $res_student;
    }

    public function insertStudent($username, $name, $password)
    {
        return $this->data([
            'student_number' => $username,
            'password' => $password,
            'name' => $name
        ])->save();
    }
}