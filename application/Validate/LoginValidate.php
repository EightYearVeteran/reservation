<?php
/**
 * Created by wangqing.
 * User: ZKRS
 * Date: 2018.4.15
 * Time: 17:29
 */

namespace app\Validate;


use app\lib\Exception\ParameterException;

class loginValidate extends BaseValidate
{
    protected $rule = [
        ['username', 'require'],
        ['password', 'require']
    ];

    protected $message = [
        'username.require' => '学号为空',
        'password.require' => '密码为空'
    ];

    public function checkUp()
    {

        $result = $this->check($this->params());

        if (!$result) {
            throw new ParameterException(['message' => $this->getError()]);
        } else {
            return true;
        }
    }
}