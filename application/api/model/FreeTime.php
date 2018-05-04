<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.27
 * Time: 20:04
 */

namespace app\api\model;


use app\lib\Exception\teacher\DatabaseOperationException;
use think\Model;

class FreeTime extends Model
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    public function insertTime($free_time)
    {
//        $freetimes_id = array();

//        foreach ($free_times as $free_time) {
        $res = $this->isUpdate(false)->data(['start_time' => $free_time['start_time'], 'end_time' => $free_time['end_time']])->save();
        if ($res)
            return $this->getLastInsID();
        else
            throw new DatabaseOperationException([
                'errorMessage' => '插入时间段数据失败'
            ]);

    }
}