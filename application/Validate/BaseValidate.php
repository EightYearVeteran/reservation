<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 17:40
 */

namespace app\Validate;


use app\lib\Exception\BaseException;
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
            throw new BaseException([
                'message' => '参数错误',
            ]);
        } else {
            return true;
        }

    }


    /**
     * 获取请求参数
     * @return mixed
     */
    public function params()
    {
        $request = Request::instance();
        $params = $request->param();

        return $params;
    }

    protected function isNotEmpty($value, $rule, $data, $field)
    {
        if (empty($value)) {
            return $field . '不能为空';
        }

        return true;
    }

}