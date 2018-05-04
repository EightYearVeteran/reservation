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

/**
 * Class FreePlace
 * @package app\api\model
 * 地点指定由管理员添加公共可以使用的所有地方
 *          教师添加私有地方(如自己办公室)
 */
class FreePlace extends Model
{
    protected $hidden = ['create_time', 'update_time', 'delete_time', 'private', 'freetime.freetime_id', 'freetime.pivot'];

    public function freetime()
    {
        return $this->belongsToMany('FreeTime', 'FreetimeFreeplace', 'freetime_id', 'freeplace_id');
    }

    public function queryOccupiedPlace($start_time, $end_time)
    {
        $res = self::with('freetime')->select();
        $freeplaces = array();
        $freeplace = array();
        foreach ($res as $item) {
            foreach ($item['freetime'] as $item_freetime) {
                //TODO: 时间处理有问题, 区间问题
                if ($item_freetime['start_time'] >= $start_time || $item_freetime['end_time'] <= $end_time) {
                    $freeplace['freeplace_id'] = $item['freeplace_id'];
                    $freeplace['freeplace'] = $item['freeplace'];
                    $freeplace['start_time'] = $item_freetime['start_time'];
                    $freeplace['end_time'] = $item_freetime['end_time'];
                    array_push($freeplaces, $freeplace);
                }
            }
        }

        return $freeplaces;
    }

    public function insertFreePlace($place_name, $flag = 0)
    {
        $res = $this->isUpdate(false)->data(['freeplace' => $place_name, 'private' => $flag])->save(); // 返回执行成功的条数1

        if ($res)
            return $this->getLastInsID();
        else
            throw new DatabaseOperationException([
                'errorMessage' => "插入自定义地点失败"
            ]);
    }

    public function queryAllPlace()
    {
        $res = $this->where('private', '=', 0)->select();
        if (count($res))
            return $res;
        else
            throw new DatabaseOperationException([
                'errorMessage' => '查询地点失败'
            ]);
    }
}