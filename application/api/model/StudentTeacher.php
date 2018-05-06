<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.5.5
 * Time: 12:30
 */

namespace app\api\model;


use app\lib\Exception\teacher\DatabaseOperationException;
use think\Model;

class StudentTeacher extends Model
{
    public function insertItem($student_id, $teacher_id, $freeplace_freetime_id)
    {
        $res = $this->isUpdate(false)->data(['student_id' => $student_id, 'teacher_id' => $teacher_id, 'freeplace_freetime_id' => $freeplace_freetime_id])->save();

        if ($res) return true;

        else throw new DatabaseOperationException([
            'errorMessage' => '插入预定失败'
        ]);
    }

    public function teacher()
    {
        return $this->belongsTo("Teacher", 'teacher_id', 'teacher_number');
    }

    public function freeplaceFreetime()
    {
        return $this->belongsTo("FreetimeFreeplace", 'freeplace_freetime_id', 'freeplace_freetime_id');
    }

    public function queryItem($student_id)
    {
        $hidden_array = ['student_teacher_id', 'create_time', 'update_time', 'delete_time', "teacher" => ['teacher_id', 'teacher_number', 'password', 'college_id', 'specialty_id'], 'freeplace_freetime' => ['freeplace_id', 'freetime_id', 'teacher_id']];

        return $this->with(['teacher', 'teacher' => ['college', 'specialty'], 'freeplaceFreetime', 'freeplaceFreetime' => ['freeplace', 'freetime']])->where("student_id", "=", $student_id)->select()->hidden($hidden_array);
    }
}