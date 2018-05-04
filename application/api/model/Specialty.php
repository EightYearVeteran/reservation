<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.5.4
 * Time: 19:34
 */

namespace app\api\model;


use think\Model;

class Specialty extends Model
{
    protected $hidden = ['create_time', 'update_time', 'delete_time', 'college_id', 'specialty_id'];


}