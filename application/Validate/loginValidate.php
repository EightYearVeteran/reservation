<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 17:29
 */

namespace app\Validate;


use app\lib\Exception\ParameterException;
use think\Request;
use think\Validate;

class loginValidate extends Validate
{
    protected $rule = [
        ['username', 'require'],
        ['password', 'require']
    ];

    protected $message = [
        'username.require' => '登录名为空',
        'password.require' => '密码为空'
    ];

    public function checkUp()
    {
        $request = Request::instance();
        $params = $request->param();
        $result = $this->check($params);

        if (!$result) {
            throw new ParameterException(['message' => $this->getError()]);
        } else {
            return true;
        }
    }
}