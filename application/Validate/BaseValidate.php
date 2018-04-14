<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.14
 * Time: 17:43
 */

namespace app\Validate;


use app\lib\Exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 请求参数验证
     */
    public function checkParameters()
    {
        $request = Request::instance();
        $params = $request->param();
        $result = $this->batch()->check($params); //true or false

        if (!$result) {
            throw new ParameterException(["message" => $this->error]);


        } else {
            return true;
        }
    }

    /**
     * @param $value 字段值
     * @param string $rule 验证规则
     * @param string $data 数据
     * @param $field 字段名
     * @return string
     */
    public function isNotEmpty($value, $rule = '', $data = '', $field)
    {
        if (empty($value)) {
            return $field . '不能为空';
        }

        return ture;
    }

}