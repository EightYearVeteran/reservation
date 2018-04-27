<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.26
 * Time: 19:04
 */

namespace app\api\model;


use app\lib\Exception\Teacher\UnregisterException;
use app\lib\Exception\teacher\WrongPasswordException;
use think\Model;

class Teacher extends Model
{
    protected $autoWriteTimeStamp = true;

    public function notRegister($teacher_number)
    {
        $res = $this->where('teacher_number', '=', $teacher_number)->find();
        if (count($res) == 0) return true;
        return false;
    }

    public function insertTeacher($teacher_number, $password, $name, $nickname)
    {
        return $this->data([
            'teacher_number' => $teacher_number,
            'password' => $password,
            'name' => $name,
            'nickname' => $nickname
        ])->save();
    }

    public function isExist($teacher_number, $password)
    {
        $res_name = $this->where('teacher_number', '=', $teacher_number)->find();
        $res_teacher = $this->where('teacher_number', '=', $teacher_number)->where('password', '=', $password)->find();

        if (count($res_name) == 0) throw new UnregisterException();

        else if (count($res_teacher) == 0) throw new WrongPasswordException();

        else return $res_teacher;
    }
}