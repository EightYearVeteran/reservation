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
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    public function college()
    {
        return $this->belongsTo("College", "college_id", "college_id");
    }

    public function specialty()
    {
        return $this->belongsTo("Specialty", "specialty_id", "specialty_id");
    }

    public function notRegister($teacher_number)
    {
        $res = $this->where('teacher_number', '=', $teacher_number)->find();
        if (count($res) == 0) return true;
        return false;
    }

    public function insertTeacher($teacher_number, $password, $name, $nickname, $college, $specialty, $email, $phone)
    {
        return $this->data([
            'teacher_number' => $teacher_number,
            'password' => $password,
            'name' => $name,
            'nickname' => $nickname,
            'college_id' => $college,
            'specialty_id' => $specialty,
            'email' => $email,
            'phone' => $phone
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

    public function queryAllTeachers($college_id)
    {
        $visible_array = ['teacher_number', 'name', 'email', 'phone', 'college', 'specialty'];

        if (empty($college_id))
            return $this->with(['college', 'specialty'])->select()->visaible($visible_array);

        return $this->with(['college', 'specialty'])
            ->where('college_id', '=', $college_id)
            ->select()
            ->visible($visible_array);

    }


    public function queryTeacherByName($teacher_name)
    {
        return $this->where('name', '=', $teacher_name)->select();
    }
}