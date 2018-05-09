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
    protected $deleteTime = 'delete_time';


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

    public function student()
    {
        return $this->belongsTo('Student', 'student_id', 'student_number');
    }

    public function freeplaceFreetime()
    {
        return $this->belongsTo("FreetimeFreeplace", 'freeplace_freetime_id', 'freeplace_freetime_id');
    }

    public function queryItem($id, $isStudent)
    {
        $hidden_array_s = ['student_teacher_id', 'create_time', 'update_time', 'delete_time', "teacher" => ['teacher_id', 'teacher_number', 'password', 'college_id', 'specialty_id'], 'freeplace_freetime' => ['freeplace_id', 'freetime_id', 'teacher_id']];
        $hidden_array_t = ['student_teacher_id', 'create_time', 'update_time', 'delete_time', "student" => ['student_id', 'create_time', 'update_time', 'delete_time', 'question', 'answer'], 'freeplace_freetime' => ['freeplace_id', 'freetime_id', 'teacher_id']];

        if ($isStudent)
            return $this->with(['teacher', 'teacher' => ['college', 'specialty'], 'freeplaceFreetime', 'freeplaceFreetime' => ['freeplace', 'freetime']])->where("student_id", "=", $id)->select()->hidden($hidden_array_s);
        else
            return $this->with(['student', 'freeplaceFreetime', 'freeplaceFreetime' => ['freeplace', 'freetime']])->where("teacher_id", "=", $id)->select()->hidden($hidden_array_t);
    }

    public function deleteItem($student_id, $teacher_id, $freeplace_freetime_id)
    {
        $res = self::where('student_id', '=', $student_id)->where('teacher_id', '=', $teacher_id)->where('freeplace_freetime_id', '=', $freeplace_freetime_id)->find()->toArray();
        $result = self::destroy($res['student_teacher_id']);
        if (!$result)
            return false;
        else return true;
    }

    public function updateComment($student_id, $teacher_id, $freeplace_freetime_id, $comment)
    {
        $res = $this->where('student_id', '=', $student_id)->where('teacher_id', '=', $teacher_id)->where('freeplace_freetime_id', '=', $freeplace_freetime_id)->update(['student_comment' => $comment]);

        if ($res == 1)
            return true;
        else
            return false;
    }
}