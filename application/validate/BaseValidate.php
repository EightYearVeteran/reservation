<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 17:40
 */

namespace app\validate;


use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    protected $errorCode;

    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()->check($params);

        if (!$result) {
            return $this->getError();
        }

        return null;
    }


    protected function isNotEmpty($value, $rule, $data, $field)
    {
        if (empty($value)) {
            return $field . '不能为空';
        }

        return true;
    }

}