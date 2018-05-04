<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:28
 */

namespace app\api\controller\v1;


use app\api\model\FreeTime;
use app\lib\Exception\FailMessage;
use app\lib\Exception\SuccessMessage;
use app\lib\Exception\teacher\AlreadyRegisteredException;
use app\lib\Exception\WrongAnswerException;
use app\validate\teacher\RegisterValidate;
use app\validate\teacher\LoginValidate;
use app\api\model\Teacher as TeacherModel;
use app\api\model\FreePlace as FreePlaceModel;
use app\api\model\FreeTime as FreeTimeModel;
use app\api\model\FreetimeFreeplace as FreetimeFreeplaceModel;

use app\validate\teacher\UsernameValidate;
use think\Controller;
use think\Request;
use think\Session;

define("PRIVATEPLACE", 1);

class Teacher extends Controller
{
    /**
     * @param string $teacher_number
     * @param string $password
     * @param string $name
     * @param string $nickname
     * @param string $college_id
     * @param string $specialty_id
     * @param string $email
     * @param string $phone
     * @throws AlreadyRegisteredException
     * @throws FailMessage
     * @throws SuccessMessage
     * @throws \app\lib\Exception\teacher\RegisterParameterException
     */
    public function register($teacher_number = '', $password = '', $name = '', $nickname = '', $college_id = '', $specialty_id = '', $email = '', $phone = '')
    {
        //TODO: 参数验证合法性
        (new RegisterValidate())->checkUp();

        $teacherModel = new TeacherModel();

        if ($teacherModel->notRegister($teacher_number)) {
            if ($teacherModel->insertTeacher($teacher_number, $password, $name, $nickname, $college_id, $specialty_id, $email, $phone))
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

    /***
     * 此方法调用前先调用queryFreepalce方法
     *
     * 前端:
     *  1. 提供时间选择(开始时间, 结束时间)
     *  2. 根据已选择的时间段, 与后端交互得到当前时间段空闲的地点
     *  3. 形如: |start_time|end_time|free_place|theme_detail|memo|
     *  4. 前端判断free_place在表单中是否重复
     *
     * thinking: 1. 如果每选一个时间段段就与后端交互获得此时间段的可用地点???
     *           2. 一次性将当前一周所有空闲地点传至前端, 然后让前端进行判断, 当用户全部填完后点击提交才将所有数据一次性提交给后端
     *           3.
     */
    /**
     * 一个时间段 对应 一个地点 对应 此次最多预约学生数 对应 预约内容 对应 备注(可选)
     * professors or teachers apply the arrange of free time in the current week
     * @param array $free_times : the array of the teachers' free time(是老师自定义的时间段)
     * @param array $free_places : the array of the reserving place(可以是place的id号, 也可以是老师自己找的地方像自己的办公室实验室等)
     * @param string $teacher_id
     * @param array $max_student : 本次预此次预约的备注约最多的学生数
     * @param array $detail : 此次预约的内容
     * @param array $memo :
     */
    public function applyForReserving()
    {
        // TODO: 时间段合法性检测
        $params = Request::instance()->post();
        $freeTimeModel = new FreeTimeModel();
        $freePlaceModel = new FreePlaceModel();
        $freeTimeFreePlaceModel = new FreetimeFreeplaceModel();

        foreach ($params as $reserving_item) {
            $freetime = $reserving_item['free_time'];
            $freeplace = $reserving_item['free_place'];
            $teacher_id = $reserving_item['teacher_id'];
            $flag = $reserving_item['flag'];
            $max_student = $reserving_item['max_student'];
            $detail = $reserving_item['detail'];
            $memo = $reserving_item['memo'];


            $freetime_id = $freeTimeModel->insertTime($freetime);

            if ($flag) { // 老师自定义地点, 先新增地点表
                $freeplace_id = $freePlaceModel->insertFreePlace($freeplace, PRIVATEPLACE);
                $res = $freeTimeFreePlaceModel->insertItem($freetime_id, $freeplace_id, $teacher_id, $max_student, $detail, $memo);
            } else {
                $res = $freeTimeFreePlaceModel->insertItem($freetime_id, $freeplace, $teacher_id, $max_student, $detail, $memo);
            }

            //TODO: 事务回滚, 如果有一个预定失败, 则将这次前端提交过来的预定都回滚
            if ($res == 0) {
                throw new FailMessage(['errorMessage' => '未知错误,预约失败']);
            }
        }
        throw new SuccessMessage(['errorMessage' => '预约成功']);
        //TODO: 达到预定结束时间后, 删除对应freetime_freeplace表中的记录
    }


    public function getAllPlaces()
    {
        $freePlaceModel = new FreePlaceModel();
        return $freePlaceModel->queryAllPlace();
    }

    /**
     * 根据老师当前选的时间段, 查看哪些地方已经被占用
     * @param $start_time current week start time
     * @param $end_time   current week end time
     * @return array
     *
     */
    public function queryOccupiedPlaces($start_time, $end_time)
    {
//        $current_week_start_time = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1);
//        $current_week_end_time = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));

        return (new FreePlaceModel())->queryOccupiedPlace($start_time, $end_time);

    }

    /**
     * update the current week free time
     */
    public function updateReserving()
    {

    }

    public function queryReserving()
    {

    }

    public function notifyMessage()
    {

    }
}