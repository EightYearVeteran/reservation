<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.5.4
 * Time: 19:35
 */

namespace app\api\model;


use think\Model;

class College extends Model
{
    protected $hidden = ['college_id', 'create_time', 'update_time', 'delete_time'];

    public function specialty()
    {
        return $this->hasMany('Specialty', 'college_id', 'college_id');
    }

    public function queryAllSpecialties()
    {
        return $this->with('specialty')->select();
    }
}