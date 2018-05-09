<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.5.4
 * Time: 19:32
 */

namespace app\api\controller\v1;


use app\api\model\College;
use think\Controller;

use app\api\model\College as CollegeModel;
use app\api\model\Teacher as TeacherModel;

class Common extends Controller
{
    public function getAllSpecialties()
    {
        return (new CollegeModel())->queryAllSpecialties();
    }

    public function getAllTeachers($college_id = '')
    {
        return (new TeacherModel())->queryAllTeachers($college_id);
    }

    public function queryTeacher($teacher_name)
    {
        return (new TeacherModel())->queryTeacherByName($teacher_name);
    }
}